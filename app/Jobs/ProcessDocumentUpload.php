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
            $structured = $data['_structured'] ?? null;
            unset($data['_structured']);

            if (
                ($data['document_kind'] ?? $document->document_kind) === 'birth_certificate'
                && blank($data['certificate_number'] ?? null)
            ) {
                $certificateNumber = $this->detectCertificateNumber($text);

                if ($certificateNumber !== null) {
                    $data['certificate_number'] = $certificateNumber;
                }
            }

            $metadata = is_array($document->metadata) ? $document->metadata : [];
            $metadata['recognition'] = [
                'processed_at' => now()->toIso8601String(),
                'parser' => 'local-structured-v2',
                'characters' => mb_strlen($text),
            ];

            if (is_array($structured)) {
                $metadata['structured'] = $structured;
            }

            // Save OCR and structured data before doing any optional archive work.
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

    private function detectCertificateNumber(string $text): ?string
    {
        $patterns = [
            '/(?:certificate\s*(?:no\.?|number)|номер\s+свидетельства|վկայական(?:ի)?\s+համար(?:ը)?)[\s:՝։#№-]*([\p{L}]{1,6}\s*\d{4,12})/ui',
            '/(?<![\p{L}\d])([\p{Armenian}]{1,6}\s*\d{5,10})(?![\p{L}\d])/u',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $match)) {
                return preg_replace('/\s+/u', '', trim($match[1])) ?: null;
            }
        }

        return null;
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
