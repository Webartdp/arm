<?php

namespace App\Services\Documents;

use Carbon\CarbonImmutable;
use Throwable;

final class DocumentDataParser
{
    public function parse(string $text): array
    {
        $lines = array_values(array_filter(
            preg_split('/\n/u', $text) ?: [],
            static fn (string $line): bool => trim($line) !== ''
        ));

        if ($this->isBirthCertificate($text)) {
            $data = $this->parseBirthCertificate($lines);
            $data['_structured'] = $this->birthCertificateStructure($data);

            return $data;
        }

        $data = $this->parseGeneric($lines);
        $data['_structured'] = $this->genericStructure($lines, $data);

        return $data;
    }

    private function isBirthCertificate(string $text): bool
    {
        return (bool) preg_match(
            '/(?:STATE\s+REGISTRATION\s+CERTIFICATE\s+OF\s+BIRTH|BIRTH\s+CERTIFICATE|СВИДЕТЕЛЬСТВ.{0,20}РОЖД|ԾՆՆԴ.{0,30}ՎԿԱՅԱԿԱՆ|ԾՆՆԴԻ)/ui',
            $text
        );
    }

    private function parseBirthCertificate(array $lines): array
    {
        $citizenStart = $this->findSection($lines, [
            '/^Citizen\b/ui', '/^Граждан/ui', '/^Քաղաքացի\b/ui',
        ]) ?? 0;

        $fatherStart = $this->findSection($lines, [
            '/^father\b/ui', '/^отец\b/ui', '/^Հայրը\b/ui',
        ]);
        $motherStart = $this->findSection($lines, [
            '/^mother\b/ui', '/^мать\b/ui', '/^Մայրը\b/ui',
        ]);
        $registrationStart = $this->findSection($lines, [
            '/registration date/ui', '/дата регистрации/ui',
            '/գրանցման\s+(?:ամսաթիվ|ժամանակ)/ui',
            '/еди(?:ном|ный).*реестр/ui',
            '/միասնական էլեկտրոնային գրանցամատյան/ui',
        ]);
        $issueStart = $this->findSection($lines, [
            '/^Վկայականը\s+տրվել\s+է/ui',
            '/^Certificate\s+(?:was\s+)?issued/ui',
            '/^Свидетельство\s+выдан/ui',
        ]);

        $citizenEnd = $fatherStart ?? $motherStart ?? $registrationStart ?? count($lines);
        $fatherEnd = $motherStart ?? $registrationStart ?? $issueStart ?? count($lines);
        $motherEnd = $registrationStart ?? $issueStart ?? count($lines);
        $registrationEnd = $issueStart ?? count($lines);

        $firstNamePatterns = ['/first\s+name/ui', '/\bимя\b/ui', '/անունը/ui'];
        $patronymicPatterns = ['/patronymic/ui', '/отчеств/ui', '/հայրանունը/ui'];
        $lastNamePatterns = ['/last\s+name/ui', '/фамили/ui', '/ազգանունը/ui'];
        $nationalityPatterns = ['/nationality/ui', '/национальност/ui', '/ազգությունը/ui'];

        $registrationDate = $this->dateValue($this->valueFor($lines, [
            '/registration date(?:\s*\([^)]*\))?/ui',
            '/дата регистрации(?:\s*\([^)]*\))?/ui',
            '/գրանցման\s+(?:ամսաթիվը?|ժամանակը?)(?:\s*\([^)]*\))?/ui',
        ], $registrationStart ?? 0, $registrationEnd));

        $data = [
            'document_kind' => 'birth_certificate',
            'document_type' => 'Birth certificate',
            'title' => 'STATE REGISTRATION CERTIFICATE OF BIRTH',
            'status' => 'active',
            'citizen_first_name' => $this->valueFor($lines, $firstNamePatterns, $citizenStart, $citizenEnd),
            'citizen_patronymic' => $this->valueFor($lines, $patronymicPatterns, $citizenStart, $citizenEnd),
            'citizen_last_name' => $this->valueFor($lines, $lastNamePatterns, $citizenStart, $citizenEnd),
            'citizen_nationality' => $this->valueFor($lines, $nationalityPatterns, $citizenStart, $citizenEnd),
            'citizen_citizenship' => $this->valueFor($lines, [
                '/citizenship/ui', '/гражданств/ui', '/քաղաքացիությունը/ui',
            ], $citizenStart, $citizenEnd),
            'birth_date' => $this->dateValue($this->valueFor($lines, [
                '/birth date(?:\s*\([^)]*\))?/ui',
                '/дата рождения(?:\s*\([^)]*\))?/ui',
                '/ծննդյան\s+(?:ամսաթիվը?|ժամանակը?)(?:\s*\([^)]*\))?/ui',
            ], 0, $fatherStart ?? count($lines))),
            'birth_place' => $this->valueFor($lines, [
                '/place of birth(?:\s*\([^)]*\))?/ui',
                '/место рождения(?:\s*\([^)]*\))?/ui',
                '/ծննդյան վայրը?(?:\s*\([^)]*\))?/ui',
            ], 0, $fatherStart ?? count($lines)),
            'father_first_name' => $fatherStart !== null ? $this->valueFor($lines, $firstNamePatterns, $fatherStart, $fatherEnd) : null,
            'father_patronymic' => $fatherStart !== null ? $this->valueFor($lines, $patronymicPatterns, $fatherStart, $fatherEnd) : null,
            'father_last_name' => $fatherStart !== null ? $this->valueFor($lines, $lastNamePatterns, $fatherStart, $fatherEnd) : null,
            'father_nationality' => $fatherStart !== null ? $this->valueFor($lines, $nationalityPatterns, $fatherStart, $fatherEnd) : null,
            'mother_first_name' => $motherStart !== null ? $this->valueFor($lines, $firstNamePatterns, $motherStart, $motherEnd) : null,
            'mother_patronymic' => $motherStart !== null ? $this->valueFor($lines, $patronymicPatterns, $motherStart, $motherEnd) : null,
            'mother_last_name' => $motherStart !== null ? $this->valueFor($lines, $lastNamePatterns, $motherStart, $motherEnd) : null,
            'mother_nationality' => $motherStart !== null ? $this->valueFor($lines, $nationalityPatterns, $motherStart, $motherEnd) : null,
            'registration_date' => $registrationDate,
            'registration_number' => $this->valueFor($lines, [
                '/registration number/ui', '/номер регистрации/ui', '/գրանցման համարը?/ui',
            ], $registrationStart ?? 0, $registrationEnd),
            'registration_authority' => $this->valueFor($lines, [
                '/registration authority/ui', '/орган регистрации/ui',
                '/գրանցման\s+(?:մարմինը?|վայրը?)/ui',
            ], $registrationStart ?? 0, $registrationEnd),
        ];

        $issueDate = $issueStart !== null
            ? $this->dateValue($this->valueFor($lines, [
                '/^ժամանակը/ui', '/տրման\s+(?:ամսաթիվը?|ժամանակը?)/ui',
                '/issue date/ui', '/дата выдачи/ui',
            ], $issueStart, count($lines)))
            : null;

        $data['issue_date'] = $issueDate ?: $registrationDate;

        $subjectParts = array_filter([
            $data['citizen_first_name'],
            $data['citizen_patronymic'],
            $data['citizen_last_name'],
        ]);
        $data['subject_name'] = $subjectParts !== [] ? implode(' ', $subjectParts) : null;

        return array_filter($data, static fn ($value): bool => $value !== null && $value !== '');
    }

    private function parseGeneric(array $lines): array
    {
        $title = null;

        foreach (array_slice($lines, 0, 8) as $line) {
            $line = trim($line);
            if (mb_strlen($line) >= 4 && mb_strlen($line) <= 255) {
                $title = $line;
                break;
            }
        }

        return array_filter([
            'document_kind' => 'generic',
            'document_type' => $title,
            'title' => $title,
            'status' => 'active',
            'issue_date' => $this->dateValue($this->valueFor($lines, [
                '/issue date/ui', '/дата выдачи/ui', '/տրման\s+(?:ամսաթիվ|ժամանակ)/ui',
            ], 0, count($lines))),
            'valid_until' => $this->dateValue($this->valueFor($lines, [
                '/valid until/ui', '/действител(?:ен|ьна|ьно) до/ui', '/վավեր է մինչև/ui',
            ], 0, count($lines))),
        ], static fn ($value): bool => $value !== null && $value !== '');
    }

    private function birthCertificateStructure(array $data): array
    {
        return [
            'schema_version' => 1,
            'kind' => 'birth_certificate',
            'title' => $data['title'] ?? 'Birth certificate',
            'sections' => array_values(array_filter([
                $this->section('Citizen', [
                    ['first name', $data['citizen_first_name'] ?? null],
                    ['patronymic', $data['citizen_patronymic'] ?? null],
                    ['last name', $data['citizen_last_name'] ?? null],
                    ['nationality', $data['citizen_nationality'] ?? null],
                    ['citizenship', $data['citizen_citizenship'] ?? null],
                ]),
                $this->section('was born', [
                    ['birth date (year-month-day)', $data['birth_date'] ?? null],
                    ['place of birth (country, region, residence)', $data['birth_place'] ?? null],
                ]),
                $this->section('father', [
                    ['first name', $data['father_first_name'] ?? null],
                    ['patronymic', $data['father_patronymic'] ?? null],
                    ['last name', $data['father_last_name'] ?? null],
                    ['nationality', $data['father_nationality'] ?? null],
                ]),
                $this->section('mother', [
                    ['first name', $data['mother_first_name'] ?? null],
                    ['patronymic', $data['mother_patronymic'] ?? null],
                    ['last name', $data['mother_last_name'] ?? null],
                    ['nationality', $data['mother_nationality'] ?? null],
                ]),
                $this->section('Registration', [
                    ['registration date (year-month-day)', $data['registration_date'] ?? null],
                    ['registration number', $data['registration_number'] ?? null],
                    ['registration authority', $data['registration_authority'] ?? null],
                ]),
            ])),
        ];
    }

    private function genericStructure(array $lines, array $data): array
    {
        $fields = [];
        $unpaired = [];
        $knownLabels = [
            'first name', 'last name', 'patronymic', 'name', 'nationality', 'citizenship',
            'date of birth', 'birth date', 'place of birth', 'issue date', 'valid until',
            'registration date', 'registration number', 'registration authority',
            'имя', 'фамилия', 'отчество', 'национальность', 'гражданство', 'дата рождения',
            'место рождения', 'дата выдачи', 'действителен до', 'номер', 'орган выдачи',
            'անունը', 'հայրանունը', 'ազգանունը', 'ազգությունը', 'քաղաքացիությունը',
            'ծննդյան ժամանակը', 'ծննդյան ամսաթիվը', 'ծննդյան վայրը',
            'գրանցման ժամանակը', 'գրանցման ամսաթիվը', 'գրանցման համարը', 'գրանցման վայրը',
            'տրամադրող մարմին', 'ժամանակը', 'սեռ', 'հծհ',
        ];

        usort($knownLabels, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if ($line === '' || $index < 2 && $line === ($data['title'] ?? null)) {
                continue;
            }

            $matched = false;
            foreach ($knownLabels as $label) {
                if (preg_match('/^' . preg_quote($label, '/') . '\b[\s:՝։-]*(.+)$/ui', $line, $m)) {
                    $value = $this->cleanValue($m[1] ?? '');
                    if ($value !== '') {
                        $fields[] = ['label' => $label, 'value' => $value];
                        $matched = true;
                        break;
                    }
                }
            }

            if ($matched) {
                continue;
            }

            if (preg_match('/^(.{2,70}?)[\s]*[:՝։][\s]*(.{1,500})$/u', $line, $m)) {
                $fields[] = [
                    'label' => $this->cleanValue($m[1]),
                    'value' => $this->cleanValue($m[2]),
                ];
                continue;
            }

            $unpaired[] = $line;
        }

        if ($fields === []) {
            foreach (array_slice($unpaired, 0, 40) as $i => $line) {
                $fields[] = ['label' => '', 'value' => $line];
            }
        }

        return [
            'schema_version' => 1,
            'kind' => 'generic',
            'title' => $data['title'] ?? $data['document_type'] ?? 'Document',
            'sections' => [[
                'title' => 'Document data',
                'fields' => $fields,
            ]],
            'unpaired_lines' => array_slice($unpaired, 0, 100),
        ];
    }

    private function section(string $title, array $pairs): ?array
    {
        $fields = [];
        foreach ($pairs as [$label, $value]) {
            if ($value === null || $value === '') {
                continue;
            }
            $fields[] = ['label' => $label, 'value' => $value];
        }

        return $fields === [] ? null : ['title' => $title, 'fields' => $fields];
    }

    private function findSection(array $lines, array $patterns): ?int
    {
        foreach ($lines as $index => $line) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $line)) {
                    return $index;
                }
            }
        }
        return null;
    }

    private function valueFor(array $lines, array $patterns, int $start, int $end): ?string
    {
        $end = min($end, count($lines));
        for ($i = max(0, $start); $i < $end; $i++) {
            foreach ($patterns as $pattern) {
                if (! preg_match($pattern, $lines[$i], $match, PREG_OFFSET_CAPTURE)) {
                    continue;
                }
                $label = $match[0][0] ?? '';
                $position = $match[0][1] ?? 0;
                $remainder = $this->cleanValue((string) substr($lines[$i], $position + strlen($label)));
                if ($this->looksLikeValue($remainder)) {
                    return $remainder;
                }
                for ($j = $i + 1; $j < min($end, $i + 4); $j++) {
                    $candidate = $this->cleanValue($lines[$j]);
                    if ($this->looksLikeValue($candidate) && ! $this->looksLikeLabel($candidate)) {
                        return $candidate;
                    }
                }
            }
        }
        return null;
    }

    private function cleanValue(string $value): string
    {
        $value = trim($value);
        $value = preg_replace('/^[\s:;,.|–—՝։]+|[\s:;,.|–—՝։]+$/u', '', $value) ?? $value;
        return trim($value);
    }

    private function looksLikeValue(string $value): bool
    {
        return $value !== '' && mb_strlen($value) <= 500;
    }

    private function looksLikeLabel(string $line): bool
    {
        return (bool) preg_match(
            '/(?:first name|last name|patronymic|nationality|citizenship|birth date|place of birth|registration date|registration number|registration authority|имя|фамили|отчеств|национальност|гражданств|дата рождения|место рождения|дата регистрации|номер регистрации|орган регистрации|անունը|հայրանունը|ազգանունը|ազգությունը|քաղաքացիությունը|ծննդյան\s+(?:ամսաթիվ|ժամանակ|վայր)|գրանցման\s+(?:ամսաթիվ|ժամանակ|համար|մարմին|վայր)|Վկայականը\s+տրվել\s+է)/ui',
            $line
        );
    }

    private function dateValue(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (preg_match('/\b(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})\b/u', $value, $match)) {
            return sprintf('%04d-%02d-%02d', (int) $match[1], (int) $match[2], (int) $match[3]);
        }
        if (preg_match('/\b(\d{1,2})[-\/.](\d{1,2})[-\/.](\d{4})\b/u', $value, $match)) {
            return sprintf('%04d-%02d-%02d', (int) $match[3], (int) $match[2], (int) $match[1]);
        }

        $armenianMonths = [
            'ՀՈՒՆՎԱՐԻ' => 1, 'ՓԵՏՐՎԱՐԻ' => 2, 'ՄԱՐՏԻ' => 3, 'ԱՊՐԻԼԻ' => 4,
            'ՄԱՅԻՍԻ' => 5, 'ՀՈՒՆԻՍԻ' => 6, 'ՀՈՒԼԻՍԻ' => 7, 'ՕԳՈՍՏՈՍԻ' => 8,
            'ՍԵՊՏԵՄԲԵՐԻ' => 9, 'ՀՈԿՏԵՄԲԵՐԻ' => 10, 'ՆՈՅԵՄԲԵՐԻ' => 11, 'ԴԵԿՏԵՄԲԵՐԻ' => 12,
        ];
        $monthPattern = implode('|', array_map(
            static fn (string $month): string => preg_quote($month, '/'),
            array_keys($armenianMonths)
        ));
        if (preg_match('/(\d{4})\s*ԹՎԱԿԱՆԻ\s*(' . $monthPattern . ')\s*(\d{1,2})\s*(?:-\s*ԻՆ)?/ui', mb_strtoupper($value), $match)) {
            $month = $armenianMonths[$match[2]] ?? null;
            if ($month !== null) {
                return sprintf('%04d-%02d-%02d', (int) $match[1], $month, (int) $match[3]);
            }
        }

        try {
            return CarbonImmutable::parse($value)->format('Y-m-d');
        } catch (Throwable) {
            return null;
        }
    }
}
