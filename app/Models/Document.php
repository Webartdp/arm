<?php

namespace App\Models;

use App\Jobs\ProcessDocumentUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'valid_until' => 'date',
            'birth_date' => 'date',
            'registration_date' => 'date',
            'processed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Document $document): void {
            if (blank($document->tracking_number)) {
                $document->tracking_number = static::generateTrackingNumber();
            }

            if (auth()->check()) {
                $document->created_by ??= auth()->id();
                $document->updated_by = auth()->id();
            }
        });

        static::saving(function (Document $document): void {
            if (filled($document->tracking_number)) {
                $document->tracking_number = static::normalizeTrackingNumber($document->tracking_number);
            }

            if ($document->exists && auth()->check()) {
                $document->updated_by = auth()->id();
            }
        });

        static::saved(function (Document $document): void {
            if ($document->wasChanged('file_path') && filled($document->file_path)) {
                $document->forceFill([
                    'processing_status' => 'queued',
                    'processing_error' => null,
                ])->saveQuietly();

                ProcessDocumentUpload::dispatch($document->getKey())->afterCommit();
            }
        });
    }

    public static function normalizeTrackingNumber(string $value): string
    {
        $plain = preg_replace('/[^A-Z0-9]/', '', strtoupper($value)) ?? '';

        return implode('-', str_split(substr($plain, 0, 16), 4));
    }

    public static function generateTrackingNumber(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $plain = '';

            for ($i = 0; $i < 16; $i++) {
                $plain .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }

            $trackingNumber = implode('-', str_split($plain, 4));
        } while (static::query()->withTrashed()->where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    public function getEffectiveStatusAttribute(): string
    {
        if (in_array($this->status, ['draft', 'revoked', 'expired'], true)) {
            return $this->status;
        }

        if ($this->valid_until?->lt(today())) {
            return 'expired';
        }

        return 'active';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
