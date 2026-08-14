@php
    $copy = [
        'en' => [
            'verified' => 'DOCUMENT IS VERIFIED',
            'download' => 'DOWNLOAD DOCUMENT',
            'search_another' => 'Search for another document',
            'generic_title' => 'DOCUMENT',
        ],
        'ru' => [
            'verified' => 'ДОКУМЕНТ ПРОВЕРЕН',
            'download' => 'СКАЧАТЬ ДОКУМЕНТ',
            'search_another' => 'Проверить другой документ',
            'generic_title' => 'ДОКУМЕНТ',
        ],
        'am' => [
            'verified' => 'ՓԱՍՏԱԹՈՒՂԹԸ ՍՏՈՒԳՎԱԾ Է',
            'download' => 'ՆԵՐԲԵՌՆԵԼ ՓԱՍՏԱԹՈՒՂԹԸ',
            'search_another' => 'Ստուգել այլ փաստաթուղթ',
            'generic_title' => 'ՓԱՍՏԱԹՈՒՂԹ',
        ],
    ][$locale] ?? null;

    $birthTitles = [
        'en' => 'STATE REGISTRATION CERTIFICATE OF BIRTH',
        'ru' => 'СВИДЕТЕЛЬСТВО О ГОСУДАРСТВЕННОЙ РЕГИСТРАЦИИ РОЖДЕНИЯ',
        'am' => 'ԾՆՆԴԻ ՊԵՏԱԿԱՆ ԳՐԱՆՑՄԱՆ ՎԿԱՅԱԿԱՆ',
    ];

    $metadata = is_array($document->metadata) ? $document->metadata : [];
    $structured = is_array($metadata['structured'] ?? null) ? $metadata['structured'] : [];
    $sections = is_array($structured['sections'] ?? null) ? $structured['sections'] : [];

    if ($document->document_kind === 'birth_certificate') {
        $documentTitle = $birthTitles[$locale] ?? $birthTitles['en'];

        $sections = array_values(array_filter([
            [
                'title' => $locale === 'am' ? 'Քաղաքացի' : ($locale === 'ru' ? 'Гражданин' : 'Citizen'),
                'fields' => [
                    ['label' => $locale === 'am' ? 'անունը' : ($locale === 'ru' ? 'имя' : 'first name'), 'value' => $document->citizen_first_name],
                    ['label' => $locale === 'am' ? 'հայրանունը' : ($locale === 'ru' ? 'отчество' : 'patronymic'), 'value' => $document->citizen_patronymic],
                    ['label' => $locale === 'am' ? 'ազգանունը' : ($locale === 'ru' ? 'фамилия' : 'last name'), 'value' => $document->citizen_last_name],
                    ['label' => $locale === 'am' ? 'ազգությունը' : ($locale === 'ru' ? 'национальность' : 'nationality'), 'value' => $document->citizen_nationality],
                    ['label' => $locale === 'am' ? 'քաղաքացիությունը' : ($locale === 'ru' ? 'гражданство' : 'citizenship'), 'value' => $document->citizen_citizenship, 'show_empty' => true],
                ],
            ],
            [
                'title' => $locale === 'am' ? 'Ծնվել է' : ($locale === 'ru' ? 'родился / родилась' : 'was born'),
                'fields' => array_values(array_filter([
                    ['label' => $locale === 'am' ? 'ծննդյան ամսաթիվը' : ($locale === 'ru' ? 'дата рождения' : 'birth date (year-month-day)'), 'value' => $document->birth_date?->format('Y-m-d')],
                    ['label' => $locale === 'am' ? 'ծննդյան վայրը' : ($locale === 'ru' ? 'место рождения' : 'place of birth (country, region, residence)'), 'value' => $document->birth_place],
                ], fn ($f) => filled($f['value'] ?? null))),
            ],
            [
                'title' => $locale === 'am' ? 'Հայրը' : ($locale === 'ru' ? 'отец' : 'father'),
                'fields' => array_values(array_filter([
                    ['label' => $locale === 'am' ? 'անունը' : ($locale === 'ru' ? 'имя' : 'first name'), 'value' => $document->father_first_name],
                    ['label' => $locale === 'am' ? 'հայրանունը' : ($locale === 'ru' ? 'отчество' : 'patronymic'), 'value' => $document->father_patronymic],
                    ['label' => $locale === 'am' ? 'ազգանունը' : ($locale === 'ru' ? 'фамилия' : 'last name'), 'value' => $document->father_last_name],
                    ['label' => $locale === 'am' ? 'ազգությունը' : ($locale === 'ru' ? 'национальность' : 'nationality'), 'value' => $document->father_nationality],
                ], fn ($f) => filled($f['value'] ?? null))),
            ],
            [
                'title' => $locale === 'am' ? 'Մայրը' : ($locale === 'ru' ? 'мать' : 'mother'),
                'fields' => array_values(array_filter([
                    ['label' => $locale === 'am' ? 'անունը' : ($locale === 'ru' ? 'имя' : 'first name'), 'value' => $document->mother_first_name],
                    ['label' => $locale === 'am' ? 'հայրանունը' : ($locale === 'ru' ? 'отчество' : 'patronymic'), 'value' => $document->mother_patronymic],
                    ['label' => $locale === 'am' ? 'ազգանունը' : ($locale === 'ru' ? 'фамилия' : 'last name'), 'value' => $document->mother_last_name],
                    ['label' => $locale === 'am' ? 'ազգությունը' : ($locale === 'ru' ? 'национальность' : 'nationality'), 'value' => $document->mother_nationality],
                ], fn ($f) => filled($f['value'] ?? null))),
            ],
            [
                'title' => $locale === 'am' ? 'Գրանցում' : ($locale === 'ru' ? 'Регистрация' : 'Registration'),
                'fields' => array_values(array_filter([
                    ['label' => $locale === 'am' ? 'գրանցման ամսաթիվը' : ($locale === 'ru' ? 'дата регистрации' : 'registration date (year-month-day)'), 'value' => $document->registration_date?->format('Y-m-d')],
                    ['label' => $locale === 'am' ? 'գրանցման համարը' : ($locale === 'ru' ? 'номер регистрации' : 'registration number'), 'value' => $document->registration_number],
                    ['label' => $locale === 'am' ? 'գրանցման մարմինը' : ($locale === 'ru' ? 'орган регистрации' : 'registration authority'), 'value' => $document->registration_authority],
                ], fn ($f) => filled($f['value'] ?? null))),
            ],
        ], fn ($section) => !empty($section['fields'])));
    } else {
        $documentTitle = $structured['title'] ?? $document->title ?? $document->document_type ?? $copy['generic_title'];
    }

    $displayCode = str_replace('-', ' ', $document->tracking_number);
    $canDownload = filled($document->download_archive_path) || filled($document->file_path);
@endphp

<style>
    body:has(.verified-source-result-marker) .loading-target > .verify-card,
    body:has(.verified-source-result-marker) .loading-target > .verify-card + .verify-card-desc {display:none!important}
    body:has(.verified-source-result-marker) .page-heading .custom-headline,
    body:has(.verified-source-result-marker) .page-heading .font-medium {display:none!important}
    body:has(.verified-source-result-marker) .page-heading {padding-bottom:0!important}
    body:has(.verified-source-result-marker) footer {margin-top:0!important}

    .verified-source-result-marker{display:block;height:0;overflow:hidden}
    .source-result-shell{position:relative;width:min(1128px,calc(100vw - 44px));margin:72px auto 0;padding:151px 0 0;background:#fff;border-radius:12px;box-shadow:0 0 15px rgba(0,0,0,.10);overflow:visible}
    .source-result-topbar{position:absolute;top:-28px;left:50%;transform:translateX(-50%);width:min(912px,calc(100% - 64px));height:60px;padding:0 25px;display:flex;align-items:center;justify-content:space-between;gap:22px;background:#f5f5f5;border-radius:8px;box-sizing:border-box}
    .source-result-code{color:#333;font-family:HelveticaNeueLTARMW05-75Bd,Arial,sans-serif;font-size:22px;line-height:1;font-weight:700;letter-spacing:3.7px;white-space:nowrap}
    .source-result-reset{display:inline-flex;align-items:center;gap:12px;color:#adadad!important;font-family:HelveticaNeueLTARMW05-55Rm,Arial,sans-serif;font-size:13px;text-decoration:none!important;white-space:nowrap}
    .source-result-reset__x{position:relative;width:21px;height:21px;display:inline-block;flex:0 0 21px}
    .source-result-reset__x:before,.source-result-reset__x:after{content:'';position:absolute;top:10px;left:1px;width:20px;height:2px;background:#adadad;border-radius:2px}
    .source-result-reset__x:before{transform:rotate(45deg)} .source-result-reset__x:after{transform:rotate(-45deg)}
    .source-result-title{position:absolute;top:61px;left:32px;right:32px;margin:0;color:#333;font-family:'GHEA Narek',Georgia,'Times New Roman',serif;font-size:35px;line-height:1.18;font-weight:400;text-align:center;text-transform:uppercase}
    .source-result-success{position:relative;width:min(912px,calc(100% - 64px));height:198px;margin:0 auto;background:#18bbb4;border-radius:12px;color:#fff}
    .source-result-success:before{content:'';position:absolute;top:-39px;left:50%;transform:translateX(-50%);width:80px;height:80px;background:#18bbb4;border-radius:50%}
    .source-result-check{position:absolute;top:-25px;left:50%;z-index:2;transform:translateX(-50%);width:30px;height:30px;border:3px solid #fff;border-radius:50%;box-sizing:border-box}
    .source-result-check:after{content:'';position:absolute;left:6px;top:6px;width:11px;height:7px;border-left:3px solid #fff;border-bottom:3px solid #fff;transform:rotate(-45deg)}
    .source-result-status{position:absolute;top:59px;left:20px;right:20px;color:#fff;font-family:HelveticaNeueLTARMW05-55Rm,Arial,sans-serif;font-size:24px;line-height:1.2;text-align:center;text-transform:uppercase}
    .source-result-download{position:absolute;top:122px;left:50%;transform:translateX(-50%);min-height:39px;padding:0 14px 0 17px;display:inline-flex;align-items:center;justify-content:center;gap:10px;border-radius:22px;background:#5c5c5c;color:#fff!important;font-family:HelveticaNeueLTARMW05-75Bd,Arial,sans-serif;font-size:11px;font-weight:700;text-decoration:none!important;text-transform:uppercase;white-space:nowrap}
    .source-result-download__arrow{position:relative;width:16px;height:18px;display:inline-block}.source-result-download__arrow:before{content:'';position:absolute;top:1px;left:7px;width:2px;height:12px;background:#fff}.source-result-download__arrow:after{content:'';position:absolute;left:3px;bottom:1px;width:9px;height:9px;border-right:2px solid #fff;border-bottom:2px solid #fff;transform:rotate(45deg)}
    .source-result-details{width:min(912px,calc(100% - 64px));margin:0 auto;padding:25px 0 52px;font-family:HelveticaNeueLTARMW05-55Rm,Arial,sans-serif}
    .source-result-section{margin:0;padding:0 0 17px;border-bottom:1px dashed #bfc4c7}.source-result-section+.source-result-section{padding-top:22px}.source-result-section:last-child{border-bottom:0}
    .source-result-section__title{margin:0 0 12px;color:#333;font-size:15px;line-height:1.35;font-weight:400}
    .source-result-row{display:grid;grid-template-columns:1fr 1fr;gap:0;padding:9px 0;font-size:13px;line-height:1.45}.source-result-row>div{padding-right:25px;color:#adadad}.source-result-row>strong{color:#333;font-weight:400;overflow-wrap:anywhere}
    .source-result-row--full{grid-template-columns:1fr}.source-result-row--full>strong{font-size:14px;line-height:1.5}
    .source-result-row--empty>strong{min-height:1em}
    .source-result-empty{padding:18px 0;color:#adadad;font-size:13px;text-align:center}

    @media(max-width:760px){
        .source-result-shell{
            width:calc(100vw - 36px);
            margin:0 auto;
            padding-top:153px;
            border-radius:0;
            box-shadow:none;
        }

        .source-result-topbar{
            top:0;
            width:calc(100% - 12px);
            height:44px;
            min-height:44px;
            padding:0 18px;
            align-items:center;
            border-radius:0 0 8px 8px;
        }
        .source-result-code{font-size:13px;letter-spacing:.8px}
        .source-result-reset{gap:0;font-size:0}
        .source-result-reset>span:first-child{display:none}
        .source-result-reset__x{width:18px;height:18px;flex-basis:18px}
        .source-result-reset__x:before,.source-result-reset__x:after{top:8px;left:2px;width:15px;height:1px}

        .source-result-title{
            top:65px;
            left:16px;
            right:16px;
            font-size:28px;
            line-height:1.05;
            font-weight:400;
        }

        .source-result-success{
            width:calc(100% - 12px);
            height:111px;
            border-radius:12px;
        }
        .source-result-success:before{top:-12px;width:48px;height:48px}
        .source-result-check{top:5px;width:18px;height:18px;border-width:2px}
        .source-result-check:after{left:4px;top:4px;width:6px;height:4px;border-left-width:2px;border-bottom-width:2px}
        .source-result-status{top:36px;font-size:13px;line-height:1.2}
        .source-result-download{top:64px;min-height:30px;padding:0 10px 0 12px;gap:7px;border-radius:16px;font-size:8px}
        .source-result-download__arrow{width:13px;height:14px}
        .source-result-download__arrow:before{top:0;left:6px;width:1px;height:9px}
        .source-result-download__arrow:after{left:3px;bottom:1px;width:7px;height:7px;border-right-width:1px;border-bottom-width:1px}

        .source-result-details{width:calc(100% - 12px);padding:7px 0 24px}
        .source-result-section{margin:0;padding:0 0 12px;border-bottom:0}
        .source-result-section+.source-result-section{padding-top:0}
        .source-result-section__title{margin:0 0 10px;color:#222;font-size:9px;line-height:1.3;font-weight:400}
        .source-result-row,
        .source-result-row--full{
            display:block;
            min-height:52px;
            margin:0 0 8px;
            padding:11px 12px 9px;
            background:#f5f5f5;
            border-radius:13px;
            box-sizing:border-box;
            font-size:11px;
            line-height:1.3;
        }
        .source-result-row>div{display:block;margin:0 0 5px;padding:0;color:#9ca3aa;font-size:9px;line-height:1.2}
        .source-result-row>strong,
        .source-result-row--full>strong{display:block;min-height:14px;color:#111;font-size:10.5px;line-height:1.35;font-weight:500;overflow-wrap:anywhere}
        .source-result-row--empty{min-height:41px;padding-bottom:8px}
        .source-result-row--empty>strong{display:none;min-height:0}
    }
</style>

<span class="verified-source-result-marker" aria-hidden="true"></span>

<div class="source-result-shell">
    <div class="source-result-topbar">
        <div class="source-result-code">{{ $displayCode }}</div>
        <a class="source-result-reset" href="{{ route('front.home', ['locale' => $locale]) }}">
            <span>{{ $copy['search_another'] }}</span>
            <span class="source-result-reset__x" aria-hidden="true"></span>
        </a>
    </div>

    <h2 class="source-result-title">{{ $documentTitle }}</h2>

    <div class="source-result-success">
        <span class="source-result-check" aria-hidden="true"></span>
        <div class="source-result-status">{{ $copy['verified'] }}</div>
        @if($canDownload)
            <a class="source-result-download" href="{{ route('front.document.download', ['locale' => $locale, 'trackingNumber' => $document->tracking_number]) }}">
                <span>{{ $copy['download'] }}</span>
                <span class="source-result-download__arrow" aria-hidden="true"></span>
            </a>
        @endif
    </div>

    <div class="source-result-details">
        @forelse($sections as $section)
            @php $fields = is_array($section['fields'] ?? null) ? $section['fields'] : []; @endphp
            @if($fields !== [])
                <section class="source-result-section">
                    @if(filled($section['title'] ?? null))
                        <h3 class="source-result-section__title">{{ $section['title'] }}</h3>
                    @endif
                    @foreach($fields as $field)
                        @php
                            $fieldValue = $field['value'] ?? null;
                            $showEmpty = !empty($field['show_empty']);
                        @endphp
                        @if(filled($fieldValue) || $showEmpty)
                            <div class="source-result-row {{ blank($field['label'] ?? null) ? 'source-result-row--full' : '' }} {{ blank($fieldValue) ? 'source-result-row--empty' : '' }}">
                                @if(filled($field['label'] ?? null))<div>{{ $field['label'] }}</div>@endif
                                <strong>{{ $fieldValue }}</strong>
                            </div>
                        @endif
                    @endforeach
                </section>
            @endif
        @empty
            <div class="source-result-empty">{{ $document->title ?: $document->document_type ?: $copy['generic_title'] }}</div>
        @endforelse
    </div>
</div>
