<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\Documents\DocumentDataParser;
use App\Services\Documents\DocumentTextExtractor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Throwable;

class ProcessDocumentUpload implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public int $timeout = 240;

    public function __construct(public int $documentId)
    {
    }

    public function handle(DocumentTextExtractor $extractor, DocumentDataParser $parser): void
    {
        $document = Document::query()->find($this->documentId);

        if (! $document || blank($document->file_path)) {
            return;
        }

        $document->forceFill([
            'processing_status' => 'processing',
            'processing_error' => null,
        ])->saveQuietly();

        try {
            $text = $extractor->extract($document);
            $data = $parser->parse($text);

            $metadata = is_array($document->metadata) ? $document->metadata : [];
            $metadata['recognition'] = [
                'processed_at' => now()->toIso8601String(),
                'parser' => 'local-ocr-v1',
                'characters' => mb_strlen($text),
            ];

            // Save OCR result first. Archive generation must never make a
            // successfully recognized document look like an OCR failure.
            $document->forceFill(array_merge($data, [
                'processing_status' => 'processed',
                'processing_error' => null,
                'extracted_text' => $text,
                'processed_at' => now(),
                'metadata' => $metadata,
            ]))->saveQuietly();

            try {
                $archivePath = $this->ensureDownloadArchive($document);
                $document->forceFill([
                    'download_archive_path' => $archivePath,
                ])->saveQuietly();
            } catch (Throwable $archiveException) {
                // The public download controller already falls back to the
                // original file, so an archive problem is non-fatal.
                $metadata = is_array($document->metadata) ? $document->metadata : [];
                $metadata['archive_error'] = mb_substr($archiveException->getMessage(), 0, 2000);

                $document->forceFill([
                    'download_archive_path' => null,
                    'metadata' => $metadata,
                ])->saveQuietly();
            }
        } catch (Throwable $e) {
            $document->forceFill([
                'processing_status' => 'failed',
                'processing_error' => mb_substr($e->getMessage(), 0, 5000),
                'processed_at' => now(),
            ])->saveQuietly();

            throw $e;
        }
    }

    private function ensureDownloadArchive(Document $document): string
    {
        $disk = Storage::disk('local');
        $sourcePath = $disk->path($document->file_path);
        $archiveRelativePath = 'document-archives/' . $document->tracking_number . '.zip';
        $archivePath = $disk->path($archiveRelativePath);

        File::ensureDirectoryExists(dirname($archivePath));

        if (is_file($archivePath)) {
            @unlink($archivePath);
        }

        $process = new Process([
            'zip',
            '-j',
            '-q',
            $archivePath,
            $sourcePath,
        ]);
        $process->setTimeout(120);
        $process->mustRun();

        return $archiveRelativePath;
    }
}
