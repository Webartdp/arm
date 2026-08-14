@php
    $copy = [
        'en' => [
            'verified' => 'DOCUMENT IS VERIFIED',
            'download' => 'DOWNLOAD DOCUMENT',
            'search_another' => 'Search for another document',
            'generic_title' => 'DOCUMENT',
        ],
        'ru' => [
            'verified' => 'ДОКУМЕНТ ДЕЙСТВИТЕЛЕН',
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

    $registryNotes = [
        'en' => 'of which an entry was made in the unified electronic register of civil status records of the Republic of Armenia',
        'ru' => 'о чем произведена запись в едином электронном реестре записей актов гражданского состояния Республики Армении',
        'am' => 'որի մասին ՀՀ քաղաքացիական կացության ակտերի գրանցման միասնական էլեկտրոնային գրանցամատյանում կատարվել է գրանցում',
    ];

    $metadata = is_array($document->metadata) ? $document->metadata : [];
    $structured = is_array($metadata['structured'] ?? null) ? $metadata['structured'] : [];
    $sections = is_array($structured['sections'] ?? null) ? $structured['sections'] : [];

    $certificateNumber = $document->certificate_number;

    if (blank($certificateNumber) && filled($document->extracted_text)) {
        if (preg_match('/(?:certificate\s*(?:no\.?|number)|номер\s+свидетельства|վկայական(?:ի)?\s+համար(?:ը)?)[\s:՝։#№-]*([\p{L}]{1,6}\s*\d{4,12})/ui', $document->extracted_text, $match)) {
            $certificateNumber = preg_replace('/\s+/u', '', $match[1]);
        } elseif (preg_match('/(?<![\p{L}\d])([\p{Armenian}]{1,6}\s*\d{5,10})(?![\p{L}\d])/u', $document->extracted_text, $match)) {
            $certificateNumber = preg_replace('/\s+/u', '', $match[1]);
        }
    }

    if ($document->document_kind === 'birth_certificate') {
        $documentTitle = $birthTitles[$locale] ?? $birthTitles['en'];

        $sections = [
            [
                'title' => $locale === 'am' ? 'Քաղաքացի' : ($locale === 'ru' ? 'Гражданин(ка)' : 'Citizen'),
                'fields' => [
                    ['label' => $locale === 'am' ? 'անունը' : ($locale === 'ru' ? 'имя' : 'first name'), 'value' => $document->citizen_first_name],
                    ['label' => $locale === 'am' ? 'հայրանունը' : ($locale === 'ru' ? 'отчество' : 'patronymic'), 'value' => $document->citizen_patronymic],
                    ['label' => $locale === 'am' ? 'ազգանունը' : ($locale === 'ru' ? 'фамилия' : 'last name'), 'value' => $document->citizen_last_name],
                    ['label' => $locale === 'am' ? 'ազգությունը' : ($locale === 'ru' ? 'национальность' : 'nationality'), 'value' => $document->citizen_nationality],
                    ['label' => $locale === 'am' ? 'քաղաքացիությունը' : ($locale === 'ru' ? 'гражданство' : 'citizenship'), 'value' => $document->citizen_citizenship, 'show_empty' => true],
                ],
            ],
            [
                'title' => $locale === 'am' ? 'Ծնվել է' : ($locale === 'ru' ? 'родился(лась)' : 'was born'),
                'fields' => array_values(array_filter([
                    ['label' => $locale === 'am' ? 'ծննդյան ամսաթիվը (տարի-ամիս-օր)' : ($locale === 'ru' ? 'дата рождения (год-месяц-день)' : 'birth date (year-month-day)'), 'value' => $document->birth_date?->format('Y-m-d')],
                    ['label' => $locale === 'am' ? 'ծննդյան վայրը (երկիր, մարզ, բնակավայր)' : ($locale === 'ru' ? 'место рождения (страна, регион, населенный пункт)' : 'place of birth (country, region, residence)'), 'value' => $document->birth_place],
                ], fn ($field) => filled($field['value'] ?? null))),
            ],
            [
                'title' => $locale === 'am' ? 'Հայրը' : ($locale === 'ru' ? 'отец' : 'father'),
                'fields' => array_values(array_filter([
                    ['label' => $locale === 'am' ? 'անունը' : ($locale === 'ru' ? 'имя' : 'first name'), 'value' => $document->father_first_name],
                    ['label' => $locale === 'am' ? 'հայրանունը' : ($locale === 'ru' ? 'отчество' : 'patronymic'), 'value' => $document->father_patronymic],
                    ['label' => $locale === 'am' ? 'ազգանունը' : ($locale === 'ru' ? 'фамилия' : 'last name'), 'value' => $document->father_last_name],
                    ['label' => $locale === 'am' ? 'ազգությունը' : ($locale === 'ru' ? 'национальность' : 'nationality'), 'value' => $document->father_nationality],
                ], fn ($field) => filled($field['value'] ?? null))),
            ],
            [
                'title' => $locale === 'am' ? 'Մայրը' : ($locale === 'ru' ? 'мать' : 'mother'),
                'fields' => array_values(array_filter([
                    ['label' => $locale === 'am' ? 'անունը' : ($locale === 'ru' ? 'имя' : 'first name'), 'value' => $document->mother_first_name],
                    ['label' => $locale === 'am' ? 'հայրանունը' : ($locale === 'ru' ? 'отчество' : 'patronymic'), 'value' => $document->mother_patronymic],
                    ['label' => $locale === 'am' ? 'ազգանունը' : ($locale === 'ru' ? 'фамилия' : 'last name'), 'value' => $document->mother_last_name],
                    ['label' => $locale === 'am' ? 'ազգությունը' : ($locale === 'ru' ? 'национальность' : 'nationality'), 'value' => $document->mother_nationality],
                ], fn ($field) => filled($field['value'] ?? null))),
            ],
            [
                'title' => null,
                'note' => $registryNotes[$locale] ?? $registryNotes['en'],
                'fields' => [],
            ],
            [
                'title' => $locale === 'am' ? 'Գրանցում' : ($locale === 'ru' ? 'Регистрация' : 'Registration'),
                'fields' => array_values(array_filter([
                    ['label' => $locale === 'am' ? 'գրանցման ամսաթիվը (տարի-ամիս-օր)' : ($locale === 'ru' ? 'дата регистрации (год-месяц-день)' : 'registration date (year-month-day)'), 'value' => $document->registration_date?->format('Y-m-d')],
                    ['label' => $locale === 'am' ? 'գրանցման համարը' : ($locale === 'ru' ? 'номер регистрации' : 'registration number'), 'value' => $document->registration_number],
                    ['label' => $locale === 'am' ? 'գրանցման վայրը' : ($locale === 'ru' ? 'место регистрации' : 'registration authority'), 'value' => $document->registration_authority],
                ], fn ($field) => filled($field['value'] ?? null))),
            ],
            [
                'title' => $locale === 'am' ? 'Վկայական' : ($locale === 'ru' ? 'Свидетельство' : 'Certificate'),
                'fields' => array_values(array_filter([
                    ['label' => $locale === 'am' ? 'վկայականի համարը' : ($locale === 'ru' ? 'номер свидетельства' : 'certificate number'), 'value' => $certificateNumber],
                ], fn ($field) => filled($field['value'] ?? null))),
            ],
        ];

        $sections = array_values(array_filter(
            $sections,
            fn ($section) => !empty($section['fields']) || filled($section['note'] ?? null)
        ));
    } else {
        $documentTitle = $structured['title'] ?? $document->title ?? $document->document_type ?? $copy['generic_title'];
    }

    $displayCode = str_replace('-', ' ', $document->tracking_number);
    $canDownload = filled($document->download_archive_path) || filled($document->file_path);
@endphp

<style>
    @font-face{
        font-family:'GHEA Narek';
        src:
            url('/static/fonts/GHEANarek.woff2') format('woff2'),
            url('/static/fonts/GHEANarek.woff') format('woff');
        font-weight:400;
        font-style:normal;
        font-display:swap;
    }

    @font-face{
        font-family:'SourceVerifyIcomoon';
        src:url(data:font/woff2;base64,d09GMgABAAAAACRcAAsAAAAATLQAACQLAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABmAAVBEICoGOfO96ATYCJAOCEAuBCgAEIAWDBgcgG+U7o6Jerl6EKMolJwP+6sB27JikkIsE+zGIZCcowH2o1BKB5fV1et5yhMY+yT0g3Wp2N40kpBIIECDJhkigJyS0ktAD0pZWFSwdEO7kBCvBdh6lWSl/KFhAO/KeZ8FCvG5veAJ2bJdDXaVm/SitvZ/kAfsAteGiJcFe9g+W8/GF0I2nlVrJPsLFY36gNmDRHDZjd+zCJSlQWBeUQAdkWwf/x3SWtA4NR7QGNaMmOv//ZsP4wz4U/t+/nXd3AfRvUWteQMlJIIihoTmtsa1QA1TPrIDsyD+795izqb0LbpxwgXkHf6+qrv+rOJCcXqhUMVP63Mo4/f+AD+ED+DwALAdSnXLuKMmFVDvSHZJcKFfJaa2AlO9MKo1Oq0ovcy23Zxs9r9kzZpiG+GvoV0AxmvlmQ3tsu+uLeNWOBzXQwKeN0cCO6Qt4pOwZAIZNIZuBDIC2o0YA+BFuhLwEFWLmfUyEcIgShOa+CMFOmU5BKNZA7lhQLRZYbhUBAu+EAbIDCXwz6UADFNB4sPhFiOnb28TBr4Y5YHpkRdJqisUGJGvkcrWOGwjdHSQcBRDtlgx91Rj5sxoD74BXo0RzcuP0CGAGVUqgNVCbMRDWBpI5aWNyHAGzJ9A4Z1lUA54AMxsa9sAGZi5nMTUAytUydTRTJJKfTCYsBwIVgKQ3h0Si9uzI6fOpHUpIjfn9/p4eNM2JP5iB9B9FURjQykflvSRHUSHClIINFxntm7kiQTXiDKpRwvKAadB5jbu41ZDJMUWUEzC/JA5KlimK8nZTJS17TAp50nEjRGksolNqnWSkrLOVdjjnq5gZQEkAGIwD8BT3Rx0OiHI130vpWxoB0ukYYJB8SKxDlVFwwVJil09BykRwbvnoT4Mh1GE7PsaFXALUDzJcNSYHanoIwwPsMBu8gOd14tV6aObLxLZpl54tHsV5RXgNB27m89CTcRB3etRyP0Jr7Ky4mKxe54NNpk97g8pql7of2zSE+n0Pe674vLtKdv2A+TRkqnUO763R/cBfwX3GVCBgdgkJ+NzBOODXe5QO+B50AHMaoSTSCFDrnBkr5qbvh6fcINieXygwKF8uJrbOAOlerhUDdJCJLsOG5NtEgkHLDrprjEEYsVRyh+5QUB/FBe0NZhBKC+m+IIq4ndUbr/zj64PBhhZeriJMj0RfGz/KlgnN011SWvSJhxa6ek4HGOlne7S8xOHtZTbYD1SP2Z1wGw7TKJtYXsYfOuWVVjIeX9s9ayjqqRFOjgPBDjo+op3gRX2vWXEYWyo2ld09k0kkHK0VYB8+EKJwSEaKf3wImJ4QjzPJWKhnknNsLmVcPMHG5GVnO2Aebs75uM/VsVlzw68fbS0iEwcewz6eqkkAkXspWFbn3B5OfGBMRKkRVadA2StWz0TledRKZMql/mI5ES3Q0L8bsEKWMrGjnPHofY/MsYgUDGAUaGpGeMIDmJ0o+hvt4BI80MwTkBSP+LOH/NzQGM+rj+vn33J6yf+1onyDouoQYmgZyllWF/pOhOcxugutxwgKJfclPZXoUS2ON08k0KKgkcbDAyy5Nk5Ki1fb47xDkUrVnYru7WUWneRQZwXRuRYkUIqiJfVTmtlZjPdeLdH6DIfscanM7W9zUmxzBRcPnhzMcla3n7iGKCu1wTtWyH5dgmWZat+hcgvoBIsw/8UEFwHJNOskHQPEozcTh7y1LWlLQp5Dc0T9MYDRuVsUM/nVhcFAZqPpA9oGsXPfpkdAVyMV/QCQE9c6tL3l1w82cK61avlL/lUyXm2T1mbAPN5most5aE6ypAlr46S1NAQ3RxaWmhQ6y6MgbQod95Of+IXjYJFgwyqEz87nPipFe7uExwjyRJ61LFMCd3ewau3v0Q/1VxGJk2yQHw6vbTqzRDc6XJwHw85IJK5AGLpWqGVIPBshrFglmZjijz3a3MDFBcymSGlRtn6dbVZZZrYt2+ArxNMaju7R2AENb1B8lk2O2aZLYtBRs6lUHgy2JmRygMDv/qFhyuLKBS3JKme/DnfvFWWMcFgBR9TtawHo3JqZqKXKJqjfj8S8EE+ONsVD8DJnTj3oUBQlh2q5k20Um7uNUCJmrikxujoimS2m5kij0Hm2p/yM7s6VVi2waQ/HMKj6o/FDy8dHc5AAjJMILxJsyHA93366axy5g/3r3WY41WonUSLY771O8JHj/cOSqefZoyaLnFbCPpMMyckpJ8zh3Uv/6p9Yn6DlpVuPP+RJbXXHP7kxySrLt9s3FwkfX/t6QR2Tj63S2fvrbPDy3Rc27rM+N598LND62jZjeWsM4ya50rm1RCMT6z3L/ruJDTZ1ZwV3n73+Db7ywu2bDr6D2pqFw2LNBwHqrTeG1OZYnqflg+dyFujCFHHkGBhiZgK7NxseKSLM8KTDqTNeOjfoZmn1H7FiEF9DUR0BD8MYE0jAK5KPXDWAmHkxJPUNmNAiDWFC6ubNXqDGEWWxeR4UmBA81K6C1yX5T1ioAQo4ybtvn0eLE6tZpDTGW9z3KTRSbm0fyk3Ub5hsii4LNGAzLIaSFbCrFKDY0b25Qj8SnW9g0O5mp1Z1o/nsHHSnkmkS4Y7cplImKmQrhmDis6j77VRWz4Y6jFbnZjc9n++MLDZLbcji1Yn6dDNHtBlAEOHVbmqEVJJbmCMgR08AFAann4Vu7guh/pYFX2RibBzqjZ2hbSSjOeeR4FMW6FybC6GGx+G8cqY4NKP00IY0sWbggyz4JKQaRQyWkQgm6LKziBYsvjJNCXW18SQIk7MAWCNtsx0WSMgv+pntOWXjYdzKmqgLNlweFAWMbLIzQFldAtQIimUxEuSbKoFlelix71F6FTRCOAUE81sMqzJEQo4oFwfcQ83OGYRDoq2YurM7NRQ35NVBPrI8HTfaYQQDcynOODGCsnohAw3y8BIzmOObfv6q2IMup0QAAiZuJGZ0kH1ZB+qbLa+KH0EHpBUpw5JpwUjsu1HSzAtIG5eymvAYWnAjpMNF+VcznKoImAWwlVsVcBrUy3Fk6dQOf8Cw/A+RR472PHWrJajfakcP1MR/EJKU6ztCMBalRCJ1A7BrNpwfOrBAHGTHobQCDrEh6jBD2cMe0jSjJ2cKzS6npRFpvu3oWYSsFDTdynJgyY/xZhsRACjyFkNW3NJca1Ka0QIbHaRTTnjI9p14oyOuSBjA4CX/uqwgaZbLecGmSNxGmInzcznlaiVRbAVe9VtfaFa+MuAlvq8jD/ujmunchYWTulf0P2OYsxM+pMhk5xSFg/4Ppvenll4xFb2TiZ1eiR9azfirlehxCAgPpSoEYTIUBWMFCtz2Emb1AKCDjE0PJnzLhm5EaaCsiOlm7MwHQB7ugCACjaNXV4bBXaVABnFTpJeHwT2b3p2AGvcuKdw4EivzAD+oIURyudB6NYNopsndpREmVTqO3VibB3vTrwZEZUVUJYnvVf3AwohRXEPAIsh5840Fhs6BLAoAil0aOt0pJDxpaZFev3UM8Qoo0rnjwKE8FEB+Q90tWTQtVy9N4jE6zRjdi2oh1TbE4okD5h4KAKawSjaaBsyUF+VBocQqDKYNxHhNYO6h4NkvTQNEsIb9MkWH7ZRBWOzDSAvcerRF+3s/Zy8KnWtLdY9HQCIo3RPuho8625jf9tcXye566hlBaj49zucXOlJ4Kzglv0VALnRNjtBiHj8Jq5P4fNnX9tAEcXEBQTO49290MomgfTzNlKffreSkPsNrm0fO+4eEhcfmb48Ol02CXzVAZvF0gLUe6MfQSNPTEfTqAGFJEs4oOyjz/Os2f0CRS/T4v4i4iJD+oFDnFWnadK0064sjl2HmGT6ptotZr6tEpZ8jaj0Yu14znllEyQWVcFaVi86oVOPFIDpAuJUW7rUku95lMtJvu7eSg0In0drorwSvc6kaHOxUL+QRHxGEkwjYCgCkBs7/vaKO4GVDbNbDQE/uNlPq3MKFAjjdaMq6TKdgnIPLpYLkczEcwsmhEAqjvF3cQ11KhqpmemwpQ2rDa+MkPu2DLioXp3qQTCQ2jtl/2zM0NLK8RBE3thzOiRh2LgU8JRuZTPJJCUrNyGCb4/eehT45bmL0A7bTIDqVm/l8x7D9eiPyWBz+o/YTiV42OkhI0RW+1JG/fZRwxsiEYBIle6/KpVo64rzTSefEcLyD6kZkNAsYbrdwVtfNALdnuM8ylyZ9D8TcP7oMb6qjGOU2dzGXmhqZaQvOSdmP4pLgP31LpVGORBoltJufm0jvV7yZHb0no3OHbjO/qciv4D9FHLgN4QuHAz4yf2Vkcz9vYbSlcZ3psjct/pekdkW1MGBxb2Qf5Rz9A1WZZ4SvPJltnhw3G60ohgxxgj1wR5lkjn5Bg0Zs3fSAJwXhBx+mXolv3tQDgxqdwe89t2OIDs4Y+VqUNTu9Z9pRn+pW91e9PAFXZnd8ctR1eGuP4z07JGFSxVLPTKsviEQEf2Ir8RcQMBJRYlav7mnt1UiJYo8z+nnVj5KCXrHvz1xWpcrXtV1zqfXWHNloyNAKI+TUUi2baluVuoKf3ITjQeZ03AA2WHx9gbaxmjbFsxFofBxhzxX8IkEz90VY3JBpm2zLJ2T1+D4gD3cYoa6j83yIZTm6xoV48n8SYHhd0cmmmHAM7cIxlvLfxHKbcEpgcbKDEQqUEhTz6NX3KeyzzjZpbZJ8D0hg0/2PcBccr0XMyFQgT4RUCjBtkB2S9e2M0JS7RL4GCZkXM8y4984rBVrw86xQPZ4Xva8RW1Jqis47/zPNGCnX0odOV9Fab7bcSkSbBAkoRNlJ896YRIEd2v6xXdJDBSK2tQzyMD+Ljloxr/hK62/RZYtGdmiwJCKM+L8C5oAwQqLlla2O+WqyfB7dary6+v8jTLjoysSdeY9jPQa7ILU2AqlVZeWK8CxBucIprCuLJK9r5gl4fUk+gn4bDyTDsuEH1OwelY2aTEIm3SR7onLtbZ0WZDqVjolcKCi8Uw890nlCdsLZGfA34sb7Dya4Bh7PwJ3waOgqgfdPd8XikkdmZuzBfQ55o+RVvCzY42bEEXSKg2ksOL0kMbGkIji4Akbpw9EK1Dgn2unsImbNirjIpq5mJ9xOVieR178G5Rm4eXmuRoa3OMd1xukmrwHwjYRUl3Xqddp1mnX+eJRK1T1NVobj03B/fYBSlHBiNEXk459ue+uS3XRVAI5r8f6t+u16TaJNX59NrJdqb4ajnxoiehyd/iGIGEdtvWN9ig5uCWhZ57gu5UXplLZxx4+n2vjtVbxKE/kGZNl+fWWXpFati1rnpYNbclvqo+q9/kalFJdKi+S4kofypdpcnbcoEfaNcQjOi9LV/7plbnC1nHM2pUbIvyC4MaZ59ygyMiRBo5bn8IADgIAkR67WPH5LacHTNZqEELuCt481nBu28nmB0fv25eaOjUVHa0LED0XTpi1wnnywzjCKjw7jw7BpXJB3O9xzabuDuH3p0ikpPrU03GPofl8/u49T4ixc6aUbicXydE+Pw4jDPepCrWW4W9eyHy3ta61ofLlAsHz8laWlwPKVQjg02bdFPMTvj9aPRPbTeh+kUg96az0A4PZDHu/BnX/zLMA05jrh5j7u6jru7jbxcp7Tw4feHu5OTu4e3ieZ2qOI5O4hebhnP8oR9EXYTXOeZvTp6xdFyvVTn/QyesHU+H8FAmdBwX/jU4JEHY+ryUEl+4FbzGle1PyP85ouBqNrjU0Dvs0HGWmMg83BS4aybz8DMDCY8KiE+plZidkzdXdR4LWfbX5eUy5k4uwSNs5caEr0itR6lSR8imELgRibyzPwOmZA3uFmuVUP48Pz5hpbYBQ3zp1nj+Z04iAjSSIjaRMsEmuJUljhxEKX6Kq2BD2L4Ty+0llps4D5d9Rkl5Bs9oEgMYk8s5+FdIjlnBJ2TQIVlozHTPaiHFLp9NQSql6nOkQ5laa5+hbtPUHk0x7e8giqqFoKYkwQuECxpsW1SD3t3Ihz9K+nX73aX1BxIyXlRkXB/mGd/jXeMXJumhpjtazBTl/gFXo0AIIgHISbLC0PurD4LQSUD97pyKQ62n9lYdJ6tV6/Xp1U2Dqi1ZNI7mV/oLDg7eILQeXSZC7CQVCIpkNKi7BFME1cYmVVIobSTwSF5KYEB+tISOizD9hqBZSzIT+29KaUqtdpD1HazLVRCEDcFH5syYTQ1gRrVtVWB8Bk2yYkc8PG4wAgWipksKFL2MaeJd2ZOmGlQDXlkFanp044zzn5U61cxVpKNbc0mNoQFKTxeq2LcdTbe9Ro99fHSwFFkMK3yzEkROd++5VG8+o2ET/i2MWrwFTvsa7jqUIhEimcnnas9/BYv6uckCJ7nZXKXmHzcUvfy7dvy0cbhX3knWU+06frxJ3IH99SR399f8eMmNOJadg99PYyX0KHvR3aK0wvtPHxKZMe5aWLQTEMQQEQmBBhtrtH7OysmSI/P7ayZvvcnRV+FgCKYBgaU+cXZS5Cj+wc1Fu4uurDDu48gs6b7RAY6DOvHj0/MBBn4eKiiW2dRyNoEPO35w8MzuWRLXb9qjj7110W5Pnt+cxvQJkz0LcFvvvjdL7Z2Itkr8ePZRkeYquctt7YgFN/5KOvbidoRkbssz0dJtzH5BAsrwhLmhm5c3AZ1txUEM/n21cGBbkvWV4zJ+T/xmWMqg+nChYyPkzZjcpOEATxxIz2niiZuXB0REVpNOfXbxgejVGvRPz1gpsQyfvzOS8S41PJJBoPi+RJU7zEhVnuyN/WzPKeQ0FVhrOxAVFM8yj/uLN1N96ompNapnWmVlYSNo7byRneN14f2cc18IjgOKFLwP5fZHN9DkhnP1memJCg7+iT2dIDPnNl9OY/LjcuHpQrkfEdG+acq9q6jclMcL97R62+c9cm/mTbtladm9PgyJchiHAONF4u9OX5FhYv57YDCn34slCNTkg4OBBCKL1BF5ySExKiQ8HPgiDG8tPixHEOCxc6dDst7S0et3CxERan8U61eHH94oXvB62mV1FlsE3wlq38nvklJqm4+L7fpFNdnb0+XLt5R7jKLq7V4PeL/xKZo228weDfm1lUDMz8wqy2/L7XuVaKV1dXNxRlPptUHCpOq92xQ4vqjt/XH5RvbWlXIgoLuVFRXOtDIOKptPjUiCu0NE84bW19Griu1kI3zuw5qubENseuiMpKwtmxlZypmjMnfzoMWjG5y68Pl0k5p9LFUS2pY2PeZylWlLM6lZ7KWOWmGEJbYKPJo+ggG2yFlkIbAMtVK/EW6U3p0u8vjVLqIpTWXrpIJU9pOF7WJB4FKMlVdEaxVOyFaQvZKlYUzt10teA3ccel8X+fphFsJYvHUrJBjIXydktBWfmOWdb//0+8p9HeE7ayK0ZyvABip45U+7RS43S6Q1Qe9ZDZEkdtnePKtQpt2ZODQp5wcJJtK2yNJkFWJmAq2eTJQAVmFH2TZw2q8ECVYwDYq768DOKSysvOHz6M6Y3Ow5yVCSfwUYP1ozgqU0aoU1CWFz02IjBaIBZGAfh/ekEnUcvnz7Pyi700clLKwOE//9QyHQUH5mRZhcn8wuL+/nzvUHijJ4gRDgIAAV3j8vDkFAtHABTERWGrso/WdnOBN7B3+E90JxVHlju4yOfZ4PIam4912p6zzMtzi3P35Vr720/a1av/UOSb3jsnQusoObnCplUX32BsG2dqfG9WWVghB8TAAf9t10mm6YkmEgBBgRMmTn5WTn9CA0ZxaanNTssqALAZmBNgsshgBhc+jEkaxfScAweQZ1v39Pw4RW0JWXh1bjxZMqXziUnMZrCuTlo+MYumOrf0yW8+33yQmGsoK1W4L/kkfZzBGKf709hk/JtE/A2fS2bTaBwyiUMTmXMYTI757TkacoP4+wYiuwg7OyD7qpSryIC4MAd8w5TqH93hKJlD48zkimRPmcLvNnPq6P/RGd/oifRXjGHGKzrDtKM8xubSxxkezis6+FShiDNnPtfCJlN8y6GXfwS5OtwS89P5VCCUTrWX/FxVJpNj7Js7r2M3m+ra6/LLlTvFGKenu6YXdPp4SLjCkfOTse2DYAoGCz3jGIMyZz6hziwJ9yiHlktI3K0ZuBh31ZtgJEg5sikZR5pAxdvnZIrUSmIApkG32LCWIdpOaadlsjraiT4jJ5MxTu91pQKCsHZ1zJ1HjGfktPQk2RdXraRo8G9iyTeczKb5j/ZOGkvJUlMoKQxMnuQoS5ZrgHp2YpbTRZMZJIxBsaAbqbRRupeoQrD9DoXVipLQqdNNZtMe60NkzcF4YVb8aZBLYlCidrJUrESNh7tZWlawXF4UK5wlt3FjKVman6do7ZQEUQKlnVbQOURto7oSgyOlfSOZjlhGZ8vq1Px3GmcPH6ZqnUcUm8huIv2bO/l/dPhnjPvcErF8zuWBHWd5U5bd5tUmKrOc4tbCRCxk6SLmaCIisiEesiPaDj4RureHWWuZ4egUzjsOgFx0F4lLu8KKEQCkcklTzxlmvNlCYp+mdL8GdkXfHOAoueFXVgky5U5hvCEEgUvudg6VjTs4lVxuJYdstmDm3sg0DWg50HfghF9daaufeltm7SjNYWn3p7ergw1h/OMIAlYqg5olJRY4nXtAksbnCUlkKz6fkNyW18r5bV/hJSnh40ISOHYL5FE20lUueDHbxsyrbbfdN4sY172pCJX2IKv4kVptn6JT1vlK7U48UB0pVVJpm2qKH0SoWbpwvx+8RJDzOcaFAQyXmBhNqSJbQ69dU+65uj22qYsxpsgu1cRUaL7KdXrq/ThHClWv+yrPApPZcZ9DcRvbY8JzcsJbe3vNzxta9uFS2Rs3VJc94SShZwdPvz45hEi4KWJJAUZK9BKncIHnzcPIJIyC8tQ8Sf9LB3f3vFynCxdfcBCMek5ro3K9TneWSmVfv3DeJ3eBh7vDy36Ex1WjPMrancxH1QGJVuKZOHWBWJIi4Q2dfP3w4ZMrKzds3Z7sHO/bE8xVvfL0zcMLCunnc/7CdTZJ33u7a/MQAaq3db2itwPKqjdsZJNMfCXt3xaiZBRz5HIBoO6Mf2wkkxkZ63+m7s6dqsDunmIms7in29UAAFxushhLFCNiFY8nwChCW4BBoXAQwFaIYQIuTytGABEnirFke3BDM9AMJ7DKNtIgjXnaWiFHFCOjCKqSSJIO2Y5sk3JDElfijY4islF0d4jjyeFwkbw9j6dafvl+YgjC5UjCSPmfQiJRoQWg8gzFyPD4+o0M258aCJ2V8OreffkNEREO03ftzjCnObmeXjs3t+GMzrzMct7XLzOk6hMN+Q4hHnYXQ7+mxfjVX7j/9s3kB8PzhuTk300jlqXMjIzZf9U3B5xWoPQ3fQBQhi1KwWZIAcNIKEaL8V33e/3CojeygsTpVnV1DRiNAthdysWx968IAKiRUvxw0YGtF5PNkikV0hftO5tT5D7X9u9P3hp68QWQpRiFdOGfuaQIqfIwHVI/8DIcPT31K+bxZFlehGjut+V/p/JUgCBwtF4flM9KY+UH6euPwiWKIM83WyXV/b38m2gu4SXL4rWeBABLKgBES2ryJMvSCIODg4FIWybJk9REA8CuWq7e/ndrnltvQnUSThKGJXEkOnST9fOpFcurvY+M9LLZvSNHvKuXr5gCVmY2io8ymAzm8T11OGOMSJ9W4B08VVtLMA/1NC9u4Z7hnmlZ3NxziHnFGbxX0foiRPJpOtEQkdYnipDLtd73CJJHiPrSCDzNp9lRJROJZCpV+M4KV7W1jZxrbwPQrY3omMqbm61VvXtvjLV3VlCj25ftYxXOjjFtNZkyxtkrFNTIaLSPUyhkRgXmAsNnXPbZkEYAgME6FMepkcEQao3j02gg0gz7Zfh+wzKw/ou7aePg4MZNAMB1s7Z24yB/Wc9nE3UODpUEUXkWqCK6um/d6u46MHi43cTaqLY2h2ZZs4iI5lJOS6sQCeH8Z2X9FwcANm4aGACbhRkrI0fLiklmvUh4kfj9i84u81wXF721D35MKT3GtZupU3bNB8UlpfyXNZowzxgIleGM5G0bD4XrRGG7UCxUzNUiM5rWWtpYfhe3THZSjFz2gu4m61X5l/sn1JY10R02m3JmtdcI6JZVdnkq7UfEiCP/psDPay27uyYvv/Wxqs3stEn9If2/1U0R9F2IdGQ3ZMaLzpVnxD+/tnmzeZaLs54WgB9jS4852GBcYG/KBsfDbNmBuqB4vywehJFnZ872GAmKyiAk81jzWL0IAFAPJie3qQEgx9Ao9LP8QTVU3/dfEdJlC50s2LffTjrU3l7vZlntvNGqYCN/qdLrJ/IuVKKJjnp5UC98MmNTl3U6Qc6j+077UKYwprreg5qR1JILUlR6LFV6TOWsd3E1zz/U/aUCcpjikXcYAPSiwJm4FGPntCjafvSEfcw0J1HMMJKfPxHp43M4LHGrQsE8tS/8xphs2/ce//vf0lMDKw8djDfk55voRbExmTqtrHaaZh5XXNiS1kG1PGibN7PSZvENnSh17wLAKgd8fj3xnlZEe098+kTkOzh/WOo8xcD9+8FFLPDBeXUVDgWebVfz5w1E/Pxnz7icj8+uVyxHZmVvXMTh1izfVPL82hTA3zB17XnJpuU/AmfRRmRWVoR6ecV1m8zZWXxnCtnZQlYZFt5A+YvSEB5WKbNwJlOc+elOALCY4S5yM9ACb+cZeP1HRLzfjTrX7KOzPfYE+ISfzo9euQJA5kce1IQDxWU8jvDySslqCmUEnlpTk4oTssJCTWzi746nyojR1GflH+cYS4gxQeTYzezsvfsiI/ftbdLNseFoBTfvNkqNBIVKocxMfdCb8qhey2w06Ijpjz8ISpwozJ1oOGE6cKB5X7yM4KNSpQnoWCjWN34a4zDPn5PU1EhyAjnmBa7jfRgJw1ASJSeb3rThzp0jiVpt4hF79oYmODYyWHgT514FTaH/Vslwz7AwTz+PUQKXVf1rZ2VmMm226rPaTAXNys58A840DQAgt6G5dcCAiRmPA9NX+0Y2CSgxKlUTpY/StF5kDEXwGMTYatDWyMvLU0u3bWOuZoaZhwVXTPxXEXzSUOkbN0pTo6LkNVpwW/+9JGn16vS/r+joJEnvMTdLFC5FVjUBAO1Ncamq7HZUVBh9KSM5Ov1rY5UH/7uvC8JXNLhVhztx4t+93nPcm9XWVl/PmgcAdIvy8ss+sphhF+2VXWtAVBmYvSY0geMECNgf3hK8eIbXLdoQ905JiSpBuHJVMn3BDxGul7llYDvPt6yMXhoSEjx9k84rVp1DobAooE4m5GlS1U4qALRrTJgwLFaqTMXwnRgAxFMPzCRmUWYeINFJWIxKSkhVm7GOgFUUpsaxRQVYMRXbKcUJ5ZEAR6EaoyuXaBxXKaPOJpaAzr0fTE08AxfOMljAdXEflOw63mSivXPg+Ja2hUB2tevlX796Rl5P/O89eSKfF5BZtirfMy5gLhocnKMhfqjYNq9irnLG5Xs3Vy5EELgb67I+bytnJsBys5wFFzqHpcMPHzypOdcoNZpMXYFtTItSPvBLLfgEl9XSSK9/we4y/RalIMaamfqY7z5wpg06+Aek1539xZ1tu6aldVHP29q3tT2LWlvW2Lqzz/5Slx7g7zDYCVcao2euzx32Y3T+YdfSuLTYBv99qGiV5SqDOC49ZWmYpNxOhh/ouQF78k9fT78zpEkgAA8RKWaP5sHHQIqgC92O3j0cHIW5WAxmhiurBZyI4X7gbW4t6LFyLBRWgw5bjAUiAIswF4xz5IqwWmwurIBExj44tFAoZMJcrBcTQC2j8sMibtCM9qPPkfRJ9ArajORj6tD/5eaCPdaNcWEvFqUoMYBv23SO/E+BEATYc2whbADj8daid0EHJOwsthoAxCTAChCAZvQi+hLxgWYM0JfgBtbY31gzIPBRNLf2Vmc+O/A9jUGC1HCvcLcasp72/KcAMJ3LSNipPYEGKGjKP69ogsYYpCeG9XUkgonKmQ/LrZGiU8NR3F9biy+L/bi5ml+HgCAqjzx+LFZknIkLTaL25IkQNzqOx1fQeYzUIfk8l7ALzsJ71djwVrxQtMpgId5Q66LGhJdgHf4TXzEh9jrEx3AbS3N0aDoIR8sIH60s2Dcut0eAynCM1hZgrFaDSbUVB5GBAQJOydXGddPBBtZV7oCQzDhrKzVGa0sxVhuESbXVB5HBEsSc0mqT56ODN/TMLl74E9VZMBuKYSHM13SIBb+Sw8/L3RPSYCYsgTIQMB8wiMEL3MFTc2QG+88f/mRIhplQChUwDwphiTT8WjLPr3LQYiaOb3aHSP+8/HRLYWaTo+KFMWLzGRwvgu9eRoznmvgbD+6AwQcAAAA=) format('woff2');
        font-weight:400;
        font-style:normal;
        font-display:block;
    }

    body:has(.verified-source-result-marker) .loading-target > .verify-card,
    body:has(.verified-source-result-marker) .loading-target > .verify-card + .verify-card-desc {display:none!important}
    body:has(.verified-source-result-marker) .page-heading .custom-headline,
    body:has(.verified-source-result-marker) .page-heading .font-medium {display:none!important}
    body:has(.verified-source-result-marker) .page-heading {padding-bottom:0!important}
    body:has(.verified-source-result-marker) footer {margin-top:0!important}

    .verified-source-result-marker{display:block;height:0;overflow:hidden}
    .source-result-shell{position:relative;width:min(1128px,calc(100vw - 44px));margin:72px auto 0;padding:1px 0 0;background:#fff;border-radius:12px;box-shadow:0 0 15px rgba(0,0,0,.10);overflow:visible}
    .source-result-topbar{position:absolute;top:-28px;left:50%;transform:translateX(-50%);width:min(912px,calc(100% - 64px));height:60px;padding:0 25px;display:flex;align-items:center;justify-content:space-between;gap:22px;background:#f5f5f5;border-radius:8px;box-sizing:border-box;z-index:3}
    .source-result-code{color:#333;font-size:22px;line-height:1;font-weight:700;letter-spacing:3.7px;white-space:nowrap}
    .source-result-reset{display:inline-flex;align-items:center;gap:12px;color:#adadad!important;font-size:13px;text-decoration:none!important;white-space:nowrap}
    .source-result-reset__x{position:relative;width:21px;height:21px;display:inline-block;flex:0 0 21px}
    .source-result-reset__x:before,.source-result-reset__x:after{content:'';position:absolute;top:10px;left:1px;width:20px;height:2px;background:#adadad;border-radius:2px}
    .source-result-reset__x:before{transform:rotate(45deg)}
    .source-result-reset__x:after{transform:rotate(-45deg)}

    .source-result-title{
        position:relative;
        margin:58px 32px 48px;
        color:#333;
        font-family:'GHEA Narek',Arial,sans-serif;
        font-size:35px;
        line-height:1.05;
        font-weight:400;
        font-style:normal;
        font-synthesis:none;
        text-align:center;
        text-transform:uppercase
    }

    .source-result-success{position:relative;width:min(912px,calc(100% - 64px));height:198px;margin:0 auto;background:#18bbb4;border-radius:12px;color:#fff}
    .source-result-success:before{content:'';position:absolute;top:-18px;left:50%;transform:translateX(-50%);width:80px;height:80px;background:#18bbb4;border-radius:50%}
    .source-result-check{position:absolute;top:2px;left:50%;z-index:2;transform:translateX(-50%);display:block;width:34px;height:34px;color:#fff;font-family:'SourceVerifyIcomoon'!important;font-size:30px;line-height:1;text-align:center;font-style:normal;font-weight:400}
    .source-result-check:before{content:'\e916'}
    .source-result-status{position:absolute;top:59px;left:20px;right:20px;color:#fff;font-size:24px;line-height:1.2;text-align:center;text-transform:uppercase}
    .source-result-download{position:absolute;top:122px;left:50%;transform:translateX(-50%);min-height:39px;padding:0 14px 0 17px;display:inline-flex;align-items:center;justify-content:center;gap:10px;border-radius:22px;background:#5c5c5c;color:#fff!important;font-size:11px;font-weight:700;text-decoration:none!important;text-transform:uppercase;white-space:nowrap}
    .source-result-download__arrow{display:inline-block;color:#fff;font-family:'SourceVerifyIcomoon'!important;font-size:16px;line-height:1;font-style:normal;font-weight:400}
    .source-result-download__arrow:before{content:'\e905'}

    .source-result-details{width:min(912px,calc(100% - 64px));margin:0 auto;padding:25px 0 52px}
    .source-result-section{margin:0;padding:0 0 17px;border-bottom:1px dashed #bfc4c7}
    .source-result-section+.source-result-section{padding-top:22px}
    .source-result-section:last-child{border-bottom:0}
    .source-result-section__title{margin:0 0 12px;color:#333;font-size:15px;line-height:1.35;font-weight:400}
    .source-result-note{padding:8px 0 16px;color:#333;font-size:13px;line-height:1.55;font-weight:400}
    .source-result-row{display:grid;grid-template-columns:1fr 1fr;gap:0;padding:9px 0;font-size:13px;line-height:1.45}
    .source-result-row>div{padding-right:25px;color:#adadad}
    .source-result-row>strong{color:#333;font-weight:400;overflow-wrap:anywhere}
    .source-result-row--full{grid-template-columns:1fr}
    .source-result-row--full>strong{font-size:14px;line-height:1.5}
    .source-result-row--empty>strong{min-height:1em}
    .source-result-empty{padding:18px 0;color:#adadad;font-size:13px;text-align:center}

    /*
     * Russian source typography.
     * The original site switches Helvetica utility classes to
     * HelveticaNeueCyr for the RU locale.
     */
    html[lang="ru"] .source-result-title{
        font-family:HelveticaNeueCyr,Arial,sans-serif!important;
        font-weight:400!important;
        font-style:normal!important;
    }

    html[lang="ru"] .source-result-reset,
    html[lang="ru"] .source-result-status,
    html[lang="ru"] .source-result-details{
        font-family:HelveticaNeueCyr,Arial,sans-serif!important;
        font-weight:400!important;
    }

    html[lang="ru"] .source-result-download{
        font-family:HelveticaNeueCyr,Arial,sans-serif!important;
        font-weight:700!important;
    }

    @media(max-width:760px){
        .source-result-shell{
            width:100%;
            margin:0;
            padding:0;
            border-radius:0;
            box-shadow:none;
        }

        .source-result-topbar{
            position:relative;
            top:auto;
            left:auto;
            transform:none;
            width:calc(100% - 8px);
            height:46px;
            min-height:46px;
            margin:0 auto;
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
            position:relative;
            margin:18px 5px 30px;
            font-size:28px;
            line-height:1.02;
            font-weight:400;
        }

        .source-result-success{
            width:calc(100% - 8px);
            height:111px;
            margin:0 auto;
            border-radius:12px;
        }
        .source-result-success:before{top:-12px;width:48px;height:48px}
        .source-result-check{top:1px;width:20px;height:20px;font-size:18px}
        .source-result-status{top:36px;font-size:13px;line-height:1.2}
        .source-result-download{top:64px;min-height:30px;padding:0 10px 0 12px;gap:7px;border-radius:16px;font-size:8px}
        .source-result-download__arrow{font-size:13px}

        .source-result-details{width:calc(100% - 8px);padding:7px 0 24px}
        .source-result-section{margin:0;padding:0 0 12px;border-bottom:0}
        .source-result-section+.source-result-section{padding-top:0}
        .source-result-section__title{margin:0 0 10px;color:#222;font-size:9px;line-height:1.3;font-weight:400}
        .source-result-note{margin:2px 8px 12px;padding:0;color:#222;font-size:9px;line-height:1.45}
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
        <div class="source-result-code helvetica-75">{{ $displayCode }}</div>
        <a class="source-result-reset helvetica-55" href="{{ route('front.home', ['locale' => $locale]) }}">
            <span>{{ $copy['search_another'] }}</span>
            <span class="source-result-reset__x" aria-hidden="true"></span>
        </a>
    </div>

    <h2 class="source-result-title">{{ $documentTitle }}</h2>

    <div class="source-result-success">
        <span class="source-result-check" aria-hidden="true"></span>
        <div class="source-result-status helvetica-55">{{ $copy['verified'] }}</div>
        @if($canDownload)
            <a class="source-result-download helvetica-75" href="{{ route('front.document.download', ['locale' => $locale, 'trackingNumber' => $document->tracking_number]) }}">
                <span>{{ $copy['download'] }}</span>
                <span class="source-result-download__arrow" aria-hidden="true"></span>
            </a>
        @endif
    </div>

    <div class="source-result-details helvetica-55">
        @forelse($sections as $section)
            @php
                $fields = is_array($section['fields'] ?? null) ? $section['fields'] : [];
                $sectionNote = $section['note'] ?? null;
            @endphp
            @if($fields !== [] || filled($sectionNote))
                <section class="source-result-section">
                    @if(filled($section['title'] ?? null))
                        <h3 class="source-result-section__title">{{ $section['title'] }}</h3>
                    @endif

                    @if(filled($sectionNote))
                        <div class="source-result-note">{{ $sectionNote }}</div>
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