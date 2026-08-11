<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\SiteSetting;
use App\Models\VerificationLog;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Throwable;

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

        $date = trim((string) $request->query('date', ''));
        $document = null;
        $verificationResult = null;

        if ($request->has('tnum') && trim((string) $request->query('tnum')) !== '') {
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

                    if (
                        $verificationResult === 'valid'
                        && $date !== ''
                        && $document->issue_date
                    ) {
                        $submittedDate = $this->parseIssueDate($date);

                        if (
                            ! $submittedDate
                            || ! $document->issue_date->isSameDay($submittedDate)
                        ) {
                            $verificationResult = 'date_mismatch';
                        }
                    }
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

    private function parseIssueDate(string $value): ?CarbonImmutable
    {
        foreach (['d/m/Y', 'Y-m-d', 'm/d/Y'] as $format) {
            try {
                $date = CarbonImmutable::createFromFormat('!' . $format, $value);

                if ($date !== false) {
                    return $date;
                }
            } catch (Throwable) {
                // Try the next known format.
            }
        }

        try {
            return CarbonImmutable::parse($value)->startOfDay();
        } catch (Throwable) {
            return null;
        }
    }
}
