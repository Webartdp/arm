<?php

namespace App\Services\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

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

    private function extractImage(string $path): string
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

                if (mb_strlen(preg_replace('/\s+/u', '', $text) ?? '') >= 20) {
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
        $process = new Process(['unzip', '-p', $path, 'word/document.xml']);
        $process->setTimeout(60);
        $process->mustRun();

        $xml = $process->getOutput();

        if ($xml === '') {
            throw new RuntimeException('DOCX не содержит word/document.xml.');
        }

        $xml = preg_replace('/<w:tab[^>]*\/>/u', "\t", $xml) ?? $xml;
        $xml = preg_replace('/<w:br[^>]*\/>/u', "\n", $xml) ?? $xml;
        $xml = preg_replace('/<\/w:p>/u', "\n", $xml) ?? $xml;
        $text = strip_tags($xml);

        return html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
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
