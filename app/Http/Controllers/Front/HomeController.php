<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\SiteSetting;
use App\Models\VerificationLog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        abort_unless(
            in_array($locale, ['am', 'en', 'ru'], true),
            404
        );

        $requestPath = parse_url(
            (string) $request->server('REQUEST_URI', ''),
            PHP_URL_PATH
        );

        if ($requestPath === '/' . $locale) {
            $target = 'https://' . $request->getHost() . '/' . $locale . '/';

            if ($request->getQueryString()) {
                $target .= '?' . $request->getQueryString();
            }

            return redirect()->away($target, 301);
        }

        $settings = SiteSetting::query()->firstOrCreate([
            'id' => 1,
        ]);

        $rawTrackingNumber = strtoupper(
            preg_replace(
                '/[^A-Z0-9]/',
                '',
                (string) $request->query('tnum', '')
            ) ?? ''
        );

        $tnum = $rawTrackingNumber !== ''
            ? implode('-', str_split(substr($rawTrackingNumber, 0, 16), 4))
            : '';

        // `date` is kept only because the existing web component submits it
        // and its presence marks an explicit Verify button click. The date
        // itself is no longer part of document validation: verification is
        // intentionally performed by the 16-character tracking code only.
        $date = trim((string) $request->query('date', ''));
        $document = null;
        $verificationResult = null;

        // QR links contain only `tnum`, so opening a QR just pre-fills the form.
        // The form submits the hidden `date` field (even when empty), therefore
        // presence of `date` means the user explicitly pressed Verify.
        $verificationRequested = $request->has('date');

        if (
            $verificationRequested
            && $request->has('tnum')
            && trim((string) $request->query('tnum')) !== ''
        ) {
            if (strlen($rawTrackingNumber) !== 16) {
                $verificationResult = 'invalid';
            } else {
                $document = Document::query()
                    ->where('tracking_number', $tnum)
                    ->first();

                if (! $document) {
                    $verificationResult = 'not_found';
                } else {
                    $verificationResult = match ($document->effective_status) {
                        'active' => 'valid',
                        'revoked' => 'revoked',
                        'expired' => 'expired',
                        'draft' => 'draft',
                        default => 'invalid',
                    };
                }
            }

            VerificationLog::query()->create([
                'document_id' => $document?->id,
                'tracking_number' => $tnum !== '' ? $tnum : null,
                'result' => $verificationResult ?? 'invalid',
                'locale' => $locale,
                'ip_hash' => $request->ip()
                    ? hash_hmac('sha256', $request->ip(), (string) config('app.key'))
                    : null,
                'user_agent' => $request->userAgent()
                    ? substr((string) $request->userAgent(), 0, 500)
                    : null,
            ]);
        }

        $view = $verificationResult === 'not_found'
            ? 'front.not-found'
            : 'front.home';

        return view($view, compact(
            'settings',
            'locale',
            'tnum',
            'date',
            'document',
            'verificationResult',
        ));
    }
}
