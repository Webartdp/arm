@php
    $copy = [
        'en' => [
            'citizen' => 'Citizen',
            'first_name' => 'first name',
            'patronymic' => 'patronymic',
            'last_name' => 'last name',
            'nationality' => 'nationality',
            'citizenship' => 'citizenship',
            'born' => 'was born',
            'birth_date' => 'birth date (year-month-day)',
            'birth_place' => 'place of birth (country, region, residence)',
            'father' => 'father',
            'mother' => 'mother',
            'registration_note' => 'about which a record has been registered in the unified electronic registry of the civil status acts of the Republic of Armenia',
            'registration_date' => 'registration date (year-month-day)',
            'registration_number' => 'registration number',
            'registration_authority' => 'registration authority',
            'certificates' => 'Certificates',
            'certificate_1' => 'Certificate 1',
            'document_title' => 'STATE REGISTRATION CERTIFICATE OF BIRTH',
            'verified' => 'DOCUMENT IS VERIFIED',
            'download' => 'DOWNLOAD DOCUMENT',
            'search_another' => 'Search for another document',
        ],
        'ru' => [
            'citizen' => 'Гражданин',
            'first_name' => 'имя',
            'patronymic' => 'отчество',
            'last_name' => 'фамилия',
            'nationality' => 'национальность',
            'citizenship' => 'гражданство',
            'born' => 'родился / родилась',
            'birth_date' => 'дата рождения (год-месяц-день)',
            'birth_place' => 'место рождения (страна, регион, населённый пункт)',
            'father' => 'отец',
            'mother' => 'мать',
            'registration_note' => 'о чем произведена запись в едином электронном реестре актов гражданского состояния Республики Армения',
            'registration_date' => 'дата регистрации (год-месяц-день)',
            'registration_number' => 'номер регистрации',
            'registration_authority' => 'орган регистрации',
            'certificates' => 'Свидетельства',
            'certificate_1' => 'Свидетельство 1',
            'document_title' => 'СВИДЕТЕЛЬСТВО О ГОСУДАРСТВЕННОЙ РЕГИСТРАЦИИ РОЖДЕНИЯ',
            'verified' => 'ДОКУМЕНТ ПРОВЕРЕН',
            'download' => 'СКАЧАТЬ ДОКУМЕНТ',
            'search_another' => 'Проверить другой документ',
        ],
        'am' => [
            'citizen' => 'Քաղաքացի',
            'first_name' => 'անունը',
            'patronymic' => 'հայրանունը',
            'last_name' => 'ազգանունը',
            'nationality' => 'ազգությունը',
            'citizenship' => 'քաղաքացիությունը',
            'born' => 'Ծնվել է',
            'birth_date' => 'ծննդյան ամսաթիվը (տարի-ամիս-օր)',
            'birth_place' => 'ծննդյան վայրը (երկիր, մարզ, բնակավայր)',
            'father' => 'Հայրը',
            'mother' => 'Մայրը',
            'registration_note' => 'որի մասին ՀՀ քաղաքացիական կացության ակտերի գրանցման միասնական էլեկտրոնային գրանցամատյանում կատարվել է գրանցում',
            'registration_date' => 'գրանցման ամսաթիվը (տարի-ամիս-օր)',
            'registration_number' => 'գրանցման համարը',
            'registration_authority' => 'գրանցման մարմինը',
            'certificates' => 'Վկայականներ',
            'certificate_1' => 'Վկայական 1',
            'document_title' => 'ԾՆՆԴԻ ՊԵՏԱԿԱՆ ԳՐԱՆՑՄԱՆ ՎԿԱՅԱԿԱՆ',
            'verified' => 'ՓԱՍՏԱԹՈՒՂԹԸ ՍՏՈՒԳՎԱԾ Է',
            'download' => 'ՆԵՐԲԵՌՆԵԼ ՓԱՍՏԱԹՈՒՂԹԸ',
            'search_another' => 'Ստուգել այլ փաստաթուղթ',
        ],
    ][$locale] ?? null;

    $display = static fn ($value) => filled($value) ? $value : '—';
    $displayCode = str_replace('-', ' - ', $document->tracking_number);
@endphp

<style>
    /* The successful birth-certificate screen replaces the search form. */
    body:has(.birth-result-marker) .loading-target > .verify-card,
    body:has(.birth-result-marker) .loading-target > .verify-card + .verify-card-desc {
        display: none !important;
    }

    /* Source screenshot: 1128px white card, 72px below the page heading. */
    body:has(.birth-result-marker) .verification-success {
        position: relative;
        width: min(1128px, calc(100vw - 44px));
        max-width: none;
        margin: 72px auto 0 !important;
        padding: 151px 0 0 !important;
        overflow: visible !important;
        border-radius: 12px !important;
        background: #fff !important;
        box-shadow: 0 0 15px rgba(0, 0, 0, .10) !important;
    }

    body:has(.birth-result-marker) .verification-success > .row {
        width: 100%;
        max-width: none;
        margin: 0 !important;
    }

    body:has(.birth-result-marker) .verification-success > .row > .column {
        width: 100% !important;
        max-width: none !important;
        flex: 0 0 100% !important;
        padding: 0 !important;
    }

    /* The native result pin is replaced by the source-style code/search bar. */
    body:has(.birth-result-marker) .verification-success .result-pin {
        display: none !important;
    }

    /* Exact source teal block: 912 × 198. */
    body:has(.birth-result-marker) .verification-success .result-card {
        position: relative !important;
        width: min(912px, calc(100% - 64px));
        max-width: none !important;
        height: 198px;
        min-height: 198px;
        margin: 0 auto !important;
        padding: 0 !important;
        border: 0 !important;
        border-radius: 12px !important;
        background: #18BBB4 !important;
        box-shadow: none !important;
        color: #fff !important;
        overflow: visible !important;
    }

    /* Keep the original 80px protruding circle from app.min.css. */
    body:has(.birth-result-marker) .verification-success .result-card:before {
        background: #18BBB4 !important;
    }

    /* In the reference this icon sits inside the protruding circle, not above it. */
    body:has(.birth-result-marker) .verification-success .result-card--img {
        position: absolute !important;
        top: 3px !important;
        left: 50% !important;
        z-index: 3;
        transform: translateX(-50%) !important;
        margin: 0 !important;
        color: #fff !important;
    }

    /* Replace the square check-filled glyph with the reference circled check. */
    body:has(.birth-result-marker) .verification-success .result-card--img i {
        position: relative;
        display: block;
        width: 30px;
        height: 30px;
        border: 3px solid #fff;
        border-radius: 50%;
        font-size: 0 !important;
    }

    body:has(.birth-result-marker) .verification-success .result-card--img i:before {
        content: '' !important;
        position: absolute;
        left: 6px;
        top: 6px;
        width: 11px;
        height: 7px;
        border-left: 3px solid #fff;
        border-bottom: 3px solid #fff;
        transform: rotate(-45deg);
    }

    body:has(.birth-result-marker) .verification-success .result-card--title,
    body:has(.birth-result-marker) .verification-success .result-card--text {
        display: none !important;
    }

    /* Data block aligns exactly with the 912px teal block. */
    body:has(.birth-result-marker) .verification-document-body {
        width: min(912px, calc(100% - 64px));
        margin: 0 auto;
        padding: 25px 0 52px !important;
    }

    body:has(.birth-result-marker) .verification-document-body > .verification-download-wrap {
        display: none !important;
    }

    .birth-result-marker {
        display: block;
        height: 0;
        overflow: hidden;
    }

    .birth-result-topbar {
        position: absolute;
        top: -28px;
        left: 50%;
        z-index: 8;
        transform: translateX(-50%);
        width: min(912px, calc(100% - 64px));
        height: 60px;
        padding: 0 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 22px;
        border-radius: 8px;
        background: #F5F5F5;
        box-sizing: border-box;
    }

    .birth-result-code {
        color: #333;
        font-family: HelveticaNeueLTARMW05-75Bd, Arial, sans-serif;
        font-size: 22px;
        line-height: 1;
        font-weight: 700;
        letter-spacing: 3.7px;
        white-space: nowrap;
    }

    .birth-result-reset {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: #ADADAD !important;
        font-family: HelveticaNeueLTARMW05-55Rm, Arial, sans-serif;
        font-size: 13px;
        line-height: 1;
        font-weight: 400;
        text-decoration: none !important;
        white-space: nowrap;
    }

    .birth-result-reset__x {
        position: relative;
        width: 21px;
        height: 21px;
        display: inline-block;
        flex: 0 0 21px;
    }

    .birth-result-reset__x::before,
    .birth-result-reset__x::after {
        content: '';
        position: absolute;
        top: 10px;
        left: 1px;
        width: 20px;
        height: 2px;
        background: #ADADAD;
        border-radius: 2px;
    }

    .birth-result-reset__x::before { transform: rotate(45deg); }
    .birth-result-reset__x::after { transform: rotate(-45deg); }

    .birth-result-title {
        position: absolute;
        top: 61px;
        left: 32px;
        right: 32px;
        margin: 0;
        color: #333;
        font-family: 'GHEA Narek', Georgia, 'Times New Roman', serif;
        font-size: 35px;
        line-height: 1.18;
        font-weight: 400;
        text-align: center;
        text-transform: uppercase;
    }

    .birth-result-status-title {
        position: absolute;
        top: 211px;
        left: 50%;
        z-index: 4;
        transform: translateX(-50%);
        width: min(760px, calc(100% - 96px));
        color: #fff;
        font-family: HelveticaNeueLTARMW05-55Rm, Arial, sans-serif;
        font-size: 24px;
        line-height: 1.2;
        font-weight: 400;
        text-align: center;
        text-transform: uppercase;
        pointer-events: none;
    }

    .birth-result-download {
        position: absolute;
        top: 273px;
        left: 50%;
        z-index: 5;
        transform: translateX(-50%);
        min-height: 39px;
        padding: 0 14px 0 17px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: 0;
        border-radius: 22px;
        background: #5C5C5C;
        color: #fff !important;
        font-family: HelveticaNeueLTARMW05-75Bd, Arial, sans-serif;
        font-size: 11px;
        line-height: 1;
        font-weight: 700;
        text-decoration: none !important;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .birth-result-download__arrow {
        position: relative;
        width: 16px;
        height: 18px;
        display: inline-block;
    }

    .birth-result-download__arrow::before {
        content: '';
        position: absolute;
        top: 1px;
        left: 7px;
        width: 2px;
        height: 12px;
        background: #fff;
    }

    .birth-result-download__arrow::after {
        content: '';
        position: absolute;
        left: 3px;
        bottom: 1px;
        width: 9px;
        height: 9px;
        border-right: 2px solid #fff;
        border-bottom: 2px solid #fff;
        transform: rotate(45deg);
    }

    .verification-source-details {
        padding: 0;
        font-family: HelveticaNeueLTARMW05-55Rm, Arial, sans-serif;
    }

    .verification-source-section {
        margin: 0;
        padding: 0 0 17px;
        border: 0;
        border-bottom: 1px dashed #BFC4C7;
    }

    .verification-source-section:first-child {
        padding-bottom: 13px;
    }

    .verification-source-section + .verification-source-section {
        padding-top: 22px;
    }

    .verification-source-section:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .verification-source-section__title {
        margin: 0 0 12px;
        color: #333;
        font-family: HelveticaNeueLTARMW05-55Rm, Arial, sans-serif;
        font-size: 15px;
        line-height: 1.35;
        font-weight: 400;
        text-transform: none;
    }

    .verification-source-section__title--note {
        max-width: 850px;
        margin-bottom: 12px;
        color: #333;
        font-size: 15px;
        line-height: 1.35;
        font-weight: 400;
    }

    .verification-source-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        padding: 9px 0;
        font-family: HelveticaNeueLTARMW05-55Rm, Arial, sans-serif;
        font-size: 13px;
        line-height: 1.45;
    }

    .verification-source-row > div {
        padding-right: 25px;
        color: #ADADAD;
        font-weight: 400;
    }

    .verification-source-row > strong {
        color: #333;
        font-family: HelveticaNeueLTARMW05-55Rm, Arial, sans-serif;
        font-weight: 400;
        overflow-wrap: anywhere;
    }

    /* Source result page has no extra 70px response-card gap before the footer. */
    body:has(.birth-result-marker) footer {
        margin-top: 0 !important;
    }

    @media (max-width: 760px) {
        body:has(.birth-result-marker) .verification-success {
            width: calc(100vw - 24px);
            margin-top: 58px !important;
            padding-top: 136px !important;
        }

        .birth-result-topbar {
            top: -22px;
            width: calc(100% - 24px);
            height: auto;
            min-height: 58px;
            padding: 13px 16px;
            align-items: flex-start;
        }

        .birth-result-code {
            font-size: 15px;
            letter-spacing: 1.5px;
        }

        .birth-result-reset {
            font-size: 11px;
        }

        .birth-result-title {
            top: 52px;
            left: 18px;
            right: 18px;
            font-size: 22px;
        }

        body:has(.birth-result-marker) .verification-success .result-card {
            width: calc(100% - 24px);
            height: 184px;
            min-height: 184px;
        }

        .birth-result-status-title {
            top: 190px;
            font-size: 19px;
        }

        .birth-result-download {
            top: 249px;
        }

        body:has(.birth-result-marker) .verification-document-body {
            width: calc(100% - 48px);
            padding-top: 23px !important;
            padding-bottom: 34px !important;
        }

        .verification-source-row {
            grid-template-columns: 45% 55%;
            padding: 7px 0;
            font-size: 12px;
        }

        .verification-source-section__title,
        .verification-source-section__title--note {
            font-size: 14px;
        }
    }
</style>

<span class="birth-result-marker" aria-hidden="true"></span>

<div class="birth-result-topbar">
    <div class="birth-result-code">{{ $displayCode }}</div>
    <a class="birth-result-reset" href="{{ route('front.home', ['locale' => $locale]) }}">
        <span>{{ $copy['search_another'] }}</span>
        <span class="birth-result-reset__x" aria-hidden="true"></span>
    </a>
</div>

<h2 class="birth-result-title">{{ $copy['document_title'] }}</h2>
<div class="birth-result-status-title">{{ $copy['verified'] }}</div>

@if($document->download_archive_path)
    <a class="birth-result-download" href="{{ route('front.document.download', ['locale' => $locale, 'trackingNumber' => $document->tracking_number]) }}">
        <span>{{ $copy['download'] }}</span>
        <span class="birth-result-download__arrow" aria-hidden="true"></span>
    </a>
@endif

<div class="verification-source-details">
    <section class="verification-source-section">
        <h3 class="verification-source-section__title">{{ $copy['citizen'] }}</h3>
        <div class="verification-source-row"><div>{{ $copy['first_name'] }}</div><strong>{{ $display($document->citizen_first_name) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['patronymic'] }}</div><strong>{{ $display($document->citizen_patronymic) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['last_name'] }}</div><strong>{{ $display($document->citizen_last_name) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['nationality'] }}</div><strong>{{ $display($document->citizen_nationality) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['citizenship'] }}</div><strong>{{ $display($document->citizen_citizenship) }}</strong></div>
    </section>

    <section class="verification-source-section">
        <h3 class="verification-source-section__title">{{ $copy['born'] }}</h3>
        <div class="verification-source-row"><div>{{ $copy['birth_date'] }}</div><strong>{{ $document->birth_date?->format('Y-m-d') ?? '—' }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['birth_place'] }}</div><strong>{{ $display($document->birth_place) }}</strong></div>
    </section>

    <section class="verification-source-section">
        <h3 class="verification-source-section__title">{{ $copy['father'] }}</h3>
        <div class="verification-source-row"><div>{{ $copy['first_name'] }}</div><strong>{{ $display($document->father_first_name) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['patronymic'] }}</div><strong>{{ $display($document->father_patronymic) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['last_name'] }}</div><strong>{{ $display($document->father_last_name) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['nationality'] }}</div><strong>{{ $display($document->father_nationality) }}</strong></div>
    </section>

    <section class="verification-source-section">
        <h3 class="verification-source-section__title">{{ $copy['mother'] }}</h3>
        <div class="verification-source-row"><div>{{ $copy['first_name'] }}</div><strong>{{ $display($document->mother_first_name) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['patronymic'] }}</div><strong>{{ $display($document->mother_patronymic) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['last_name'] }}</div><strong>{{ $display($document->mother_last_name) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['nationality'] }}</div><strong>{{ $display($document->mother_nationality) }}</strong></div>
    </section>

    <section class="verification-source-section verification-source-registration">
        <h3 class="verification-source-section__title verification-source-section__title--note">{{ $copy['registration_note'] }}</h3>
        <div class="verification-source-row"><div>{{ $copy['registration_date'] }}</div><strong>{{ $document->registration_date?->format('Y-m-d') ?? '—' }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['registration_number'] }}</div><strong>{{ $display($document->registration_number) }}</strong></div>
        <div class="verification-source-row"><div>{{ $copy['registration_authority'] }}</div><strong>{{ $display($document->registration_authority) }}</strong></div>
    </section>

    <section class="verification-source-section">
        <h3 class="verification-source-section__title">{{ $copy['certificates'] }}</h3>
        <div class="verification-source-row"><div>{{ $copy['certificate_1'] }}</div><strong>{{ $display($document->certificate_number) }}</strong></div>
    </section>
</div>