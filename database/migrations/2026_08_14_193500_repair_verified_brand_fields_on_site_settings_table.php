<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'verified_service_name_ru',
            'verified_country_ru',
            'verified_subtitle_ru',
            'verified_service_name_en',
            'verified_country_en',
            'verified_subtitle_en',
            'verified_service_name_am',
            'verified_country_am',
            'verified_subtitle_am',
        ];

        foreach ($columns as $name) {
            if (Schema::hasColumn('site_settings', $name)) {
                continue;
            }

            Schema::table('site_settings', function (Blueprint $table) use ($name): void {
                // Use TEXT because site_settings is already near InnoDB's
                // maximum inline row size and extra VARCHAR columns can fail
                // with SQLSTATE 1118 (Row size too large).
                $table->text($name)->nullable();
            });
        }

        if (DB::table('site_settings')->where('id', 1)->exists()) {
            $defaults = [
                'verified_service_name_ru' => 'arm.gov.e-verify.net',
                'verified_country_ru' => 'Республика Армения',
                'verified_subtitle_ru' => 'единая система проверки действительности официальных документов',
                'verified_service_name_en' => 'arm.gov.e-verify.net',
                'verified_country_en' => 'Republic of Armenia',
                'verified_subtitle_en' => 'unified system for checking the validity of official documents',
                'verified_service_name_am' => 'arm.gov.e-verify.net',
                'verified_country_am' => 'Հայաստանի Հանրապետություն',
                'verified_subtitle_am' => 'պաշտոնական փաստաթղթերի վավերականության ստուգման միասնական համակարգ',
            ];

            $record = DB::table('site_settings')->where('id', 1)->first();
            $updates = [];

            foreach ($defaults as $column => $value) {
                if (blank($record->{$column} ?? null)) {
                    $updates[$column] = $value;
                }
            }

            if ($updates !== []) {
                DB::table('site_settings')->where('id', 1)->update($updates);
            }
        }
    }

    public function down(): void
    {
        // Repair migration intentionally leaves the columns in place.
        // They belong to the verified-brand feature and may have been created
        // by the original migration on other environments.
    }
};
