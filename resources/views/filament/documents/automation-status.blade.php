@php
    $status = $record?->processing_status ?? 'not_processed';
    $statusLabel = match ($status) {
        'queued' => 'В очереди на распознавание',
        'processing' => 'Распознаётся…',
        'processed' => 'Готово',
        'failed' => 'Ошибка распознавания',
        default => 'Файл ещё не обрабатывался',
    };

    $verificationUrl = $record
        ? route('front.home', ['locale' => 'en', 'tnum' => $record->tracking_number])
        : null;

    $qrUrl = $verificationUrl
        ? 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&format=png&margin=10&data=' . rawurlencode($verificationUrl)
        : null;
@endphp

@if($record)
    <div style="display:grid;grid-template-columns:minmax(0,1fr) 280px;gap:24px;align-items:start;padding:4px 0;">
        <div>
            <div style="font-size:13px;color:#6b7280;margin-bottom:6px;">Автоматическая обработка</div>
            <div style="font-size:18px;font-weight:700;margin-bottom:14px;">{{ $statusLabel }}</div>

            <div style="display:grid;grid-template-columns:160px minmax(0,1fr);gap:8px 18px;font-size:14px;line-height:1.5;">
                <div style="color:#6b7280;">Код проверки</div>
                <div style="font-weight:600;letter-spacing:.08em;">{{ $record->tracking_number }}</div>

                <div style="color:#6b7280;">Тип документа</div>
                <div>{{ $record->document_type ?: ($record->document_kind === 'birth_certificate' ? 'Свидетельство о рождении' : 'Определяется автоматически') }}</div>

                @if($record->processed_at)
                    <div style="color:#6b7280;">Обработан</div>
                    <div>{{ $record->processed_at->format('d.m.Y H:i') }}</div>
                @endif

                <div style="color:#6b7280;">Ссылка проверки</div>
                <div style="overflow-wrap:anywhere;">
                    <a href="{{ $verificationUrl }}" target="_blank" rel="noreferrer" style="text-decoration:underline;">{{ $verificationUrl }}</a>
                </div>
            </div>

            @if($record->processing_error)
                <div style="margin-top:16px;padding:12px 14px;border-radius:8px;background:#fef2f2;color:#991b1b;font-size:13px;line-height:1.5;">
                    {{ $record->processing_error }}
                </div>
            @elseif($status === 'processed')
                <div style="margin-top:16px;padding:12px 14px;border-radius:8px;background:#ecfdf5;color:#065f46;font-size:13px;line-height:1.5;">
                    Документ распознан. Все извлечённые данные сохранены автоматически и используются на публичной странице проверки.
                </div>
            @else
                <div style="margin-top:16px;padding:12px 14px;border-radius:8px;background:#eff6ff;color:#1e40af;font-size:13px;line-height:1.5;">
                    После загрузки файла распознавание запускается автоматически. Никакие дополнительные поля заполнять не нужно.
                </div>
            @endif
        </div>

        @if($qrUrl)
            <div style="text-align:center;">
                <div style="font-size:13px;color:#6b7280;margin-bottom:10px;">QR-код проверки</div>
                <div style="display:inline-block;padding:10px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <img src="{{ $qrUrl }}" alt="QR {{ $record->tracking_number }}" width="220" height="220" style="display:block;">
                </div>
                <div style="margin-top:10px;">
                    <a href="{{ $qrUrl }}" target="_blank" rel="noreferrer" style="font-size:13px;text-decoration:underline;">Открыть QR в полном размере</a>
                </div>
            </div>
        @endif
    </div>

    <style>
        @media (max-width: 900px) {
            .fi-section-content-ctn > div > div[style*="grid-template-columns:minmax(0,1fr) 280px"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@else
    <div style="font-size:14px;color:#6b7280;line-height:1.6;">
        Загрузите документ и нажмите «Создать». Код проверки, распознавание и QR-код будут созданы автоматически.
    </div>
@endif
