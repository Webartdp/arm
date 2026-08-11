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
            'registration_note' => 'Registration details stated in the document',
            'registration_date' => 'registration date (year-month-day)',
            'registration_number' => 'registration number',
            'registration_authority' => 'registration authority',
            'certificates' => 'Certificates',
            'certificate_1' => 'Certificate 1',
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
            'registration_note' => 'Регистрационные данные, указанные в документе',
            'registration_date' => 'дата регистрации (год-месяц-день)',
            'registration_number' => 'номер регистрации',
            'registration_authority' => 'орган регистрации',
            'certificates' => 'Свидетельства',
            'certificate_1' => 'Свидетельство 1',
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
            'registration_note' => 'Փաստաթղթում նշված գրանցման տվյալները',
            'registration_date' => 'գրանցման ամսաթիվը (տարի-ամիս-օր)',
            'registration_number' => 'գրանցման համարը',
            'registration_authority' => 'գրանցման մարմինը',
            'certificates' => 'Վկայականներ',
            'certificate_1' => 'Վկայական 1',
        ],
    ][$locale] ?? null;

    $display = static fn ($value) => filled($value) ? $value : '—';
@endphp

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
