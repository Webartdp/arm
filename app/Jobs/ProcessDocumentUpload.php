<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\Documents\DocumentDataParser;
use App\Services\Documents\DocumentTextExtractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable as FoundationQueueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessDocumentUpload implements ShouldQueue
{
    use FoundationQueueable, InteractsWithQueue, Queueable, SerializesModels;

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

            $payload = array_merge($data, [
                'processing_status' => 'processed',
                'processing_error' => null,
                'extracted_text' => $text,
                'processed_at' => now(),
                'metadata' => $metadata,
            ]);

            $document->forceFill($payload)->saveQuietly();
        } catch (Throwable $e) {
            $document->forceFill([
                'processing_status' => 'failed',
                'processing_error' => mb_substr($e->getMessage(), 0, 5000),
                'processed_at' => now(),
            ])->saveQuietly();

            throw $e;
        }
    }
}
