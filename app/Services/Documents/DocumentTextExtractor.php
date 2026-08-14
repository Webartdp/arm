<?php

namespace App\Services\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

final class DocumentTextExtractor
{
    public function extract(Document $document): string
    {
        if (blank($document->file_path)) {
            throw new RuntimeException('У документа не загружен исходный файл.');
        }

        $disk = Storage::disk('local');

        if (! $disk->exists($document->file_path)) {
            throw new RuntimeException('Исходный файл документа не найден в storage.');
        }

        $path = $disk->path($document->file_path);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $text = match ($extension) {
            'pdf' => $this->extractPdf($path),
            'jpg', 'jpeg', 'png', 'webp' => $this->extractImage($path),
            'docx' => $this->extractDocx($path),
            default => throw new RuntimeException("Неподдерживаемый формат файла: {$extension}"),
        };

        $text = $this->normalize($text);

        if (mb_strlen(preg_replace('/\s+/u', '', $text) ?? '') < 20) {
            throw new RuntimeException('Не удалось получить достаточно текста из документа.');
        }

        return $text;
    }

    private function extractPdf(string $path): string
    {
        $textProcess = new Process(['pdftotext', '-layout', '-nopgbrk', $path, '-']);
        $textProcess->setTimeout(60);
        $textProcess->run();

        if ($textProcess->isSuccessful()) {
            $text = $this->normalize($textProcess->getOutput());

            if (mb_strlen(preg_replace('/\s+/u', '', $text) ?? '') >= 80) {
                return $text;
            }
        }

        $tmpDir = storage_path('app/tmp/document-ocr-' . Str::uuid());
        File::ensureDirectoryExists($tmpDir);

        try {
            $prefix = $tmpDir . '/page';
            $render = new Process([
                'pdftoppm',
                '-jpeg',
                '-r',
                '220',
                '-f',
                '1',
                '-l',
                '10',
                $path,
                $prefix,
            ]);
            $render->setTimeout(120);
            $render->mustRun();

            $pages = glob($prefix . '-*.jpg') ?: [];
            natsort($pages);

            if ($pages === []) {
                throw new RuntimeException('Не удалось преобразовать PDF в изображения для OCR.');
            }

            $chunks = [];

            foreach ($pages as $page) {
                $chunks[] = $this->extractImage($page);
            }

            return implode("\n\n", $chunks);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    private function extractImage(string $path, int $minCharacters = 20): string
    {
        $languages = [
            'hye+rus+eng',
            'hye+eng',
            'rus+eng',
            'eng',
        ];

        $errors = [];

        foreach ($languages as $language) {
            $process = new Process([
                'tesseract',
                $path,
                'stdout',
                '-l',
                $language,
                '--oem',
                '1',
                '--psm',
                '6',
            ]);
            $process->setTimeout(120);
            $process->run();

            if ($process->isSuccessful()) {
                $text = $this->normalize($process->getOutput());

                if (mb_strlen(preg_replace('/\s+/u', '', $text) ?? '') >= $minCharacters) {
                    return $text;
                }
            }

            $errors[] = trim($process->getErrorOutput());
        }

        throw new RuntimeException(
            'Tesseract не смог распознать изображение. ' . trim(implode(' ', array_filter($errors)))
        );
    }

    private function extractDocx(string $path): string
    {
        $list = new Process(['unzip', '-Z1', $path]);
        $list->setTimeout(60);
        $list->mustRun();

        $members = array_values(array_filter(
            preg_split('/\R/u', trim($list->getOutput())) ?: [],
            static fn (string $member): bool => $member !== ''
        ));

        $xmlMembers = array_values(array_filter(
            $members,
            static fn (string $member): bool => (bool) preg_match(
                '#^word/(?:document|header\d+|footer\d+|footnotes|endnotes|comments)\.xml$#i',
                $member
            )
        ));

        usort($xmlMembers, static function (string $a, string $b): int {
            $priority = static function (string $member): int {
                return match (true) {
                    $member === 'word/document.xml' => 0,
                    str_contains($member, '/header') => 1,
                    str_contains($member, '/footer') => 2,
                    default => 3,
                };
            };

            return [$priority($a), $a] <=> [$priority($b), $b];
        });

        if (! in_array('word/document.xml', $xmlMembers, true)) {
            throw new RuntimeException('DOCX не содержит word/document.xml.');
        }

        $chunks = [];

        foreach ($xmlMembers as $member) {
            $process = new Process(['unzip', '-p', $path, $member]);
            $process->setTimeout(60);
            $process->run();

            if (! $process->isSuccessful() || $process->getOutput() === '') {
                continue;
            }

            $text = $this->docxXmlToText($process->getOutput());

            if ($this->nonSpaceLength($text) >= 2) {
                $chunks[] = $text;
            }
        }

        $mediaMembers = array_slice(array_values(array_filter(
            $members,
            static fn (string $member): bool => (bool) preg_match(
                '#^word/media/.+\.(?:png|jpe?g|webp|tif|tiff|bmp)$#i',
                $member
            )
        )), 0, 20);

        if ($mediaMembers !== []) {
            $tmpDir = storage_path('app/tmp/document-docx-media-' . Str::uuid());
            File::ensureDirectoryExists($tmpDir);

            try {
                foreach ($mediaMembers as $index => $member) {
                    $extension = strtolower(pathinfo($member, PATHINFO_EXTENSION));
                    $target = $tmpDir . '/' . $index . '.' . $extension;

                    $process = new Process(['unzip', '-p', $path, $member]);
                    $process->setTimeout(60);
                    $process->run();

                    if (! $process->isSuccessful() || $process->getOutput() === '') {
                        continue;
                    }

                    File::put($target, $process->getOutput());

                    try {
                        $mediaText = $this->extractImage($target, 4);

                        if ($this->nonSpaceLength($mediaText) >= 4) {
                            $chunks[] = $mediaText;
                        }
                    } catch (Throwable) {
                        // Decorative images and logos are allowed to contain no readable text.
                    }
                }
            } finally {
                File::deleteDirectory($tmpDir);
            }
        }

        $text = $this->normalize(implode("\n\n", $chunks));

        if ($this->nonSpaceLength($text) < 20) {
            throw new RuntimeException('Не удалось получить достаточно текста из DOCX.');
        }

        return $text;
    }

    private function docxXmlToText(string $xml): string
    {
        $xml = preg_replace('/<w:tab[^>]*\/>/u', "\t", $xml) ?? $xml;
        $xml = preg_replace('/<w:br[^>]*\/>/u', "\n", $xml) ?? $xml;
        $xml = preg_replace('/<\/w:p>/u', "\n", $xml) ?? $xml;
        $text = strip_tags($xml);

        return html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function nonSpaceLength(string $text): int
    {
        return mb_strlen(preg_replace('/\s+/u', '', $text) ?? '');
    }

    private function normalize(string $text): string
    {
        $text = str_replace(["\r\n", "\r", "\xC2\xA0"], ["\n", "\n", ' '], $text);
        $lines = preg_split('/\n/u', $text) ?: [];
        $normalized = [];
        $blank = false;

        foreach ($lines as $line) {
            $line = trim(preg_replace('/[\t ]+/u', ' ', $line) ?? $line);

            if ($line === '') {
                if (! $blank && $normalized !== []) {
                    $normalized[] = '';
                }

                $blank = true;
                continue;
            }

            $normalized[] = $line;
            $blank = false;
        }

        return trim(implode("\n", $normalized));
    }
}
