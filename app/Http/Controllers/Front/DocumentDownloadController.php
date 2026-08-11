<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentDownloadController extends Controller
{
    public function __invoke(string $locale, string $trackingNumber): StreamedResponse
    {
        abort_unless(in_array($locale, ['am', 'en', 'ru'], true), 404);

        $plain = preg_replace('/[^A-Z0-9]/', '', strtoupper($trackingNumber)) ?? '';
        abort_unless(strlen($plain) === 16, 404);

        $tnum = Document::normalizeTrackingNumber($trackingNumber);

        $document = Document::query()
            ->where('tracking_number', $tnum)
            ->firstOrFail();

        abort_unless($document->effective_status === 'active', 404);
        abort_unless(filled($document->download_archive_path), 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($document->download_archive_path), 404);

        return $disk->download(
            $document->download_archive_path,
            $document->tracking_number . '.zip',
            [
                'Content-Type' => 'application/zip',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store, max-age=0',
            ]
        );
    }
}
