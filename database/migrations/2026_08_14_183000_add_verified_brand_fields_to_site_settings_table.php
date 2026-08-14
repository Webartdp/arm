<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            foreach (['ru', 'en', 'am'] as $locale) {
                $table->string("verified_service_name_{$locale}")->nullable();
                $table->string("verified_country_{$locale}")->nullable();
                $table->string("verified_subtitle_{$locale}", 500)->nullable();
            }
        });

        DB::table('site_settings')->where('id', 1)->update([
            'verified_service_name_ru' => 'arm.gov.e-verify.net',
            'verified_country_ru' => 'Республика Армения',
            'verified_subtitle_ru' => 'единая система проверки действительности официальных документов',
            'verified_service_name_en' => 'arm.gov.e-verify.net',
            'verified_country_en' => 'Republic of Armenia',
            'verified_subtitle_en' => 'unified system for checking the validity of official documents',
            'verified_service_name_am' => 'arm.gov.e-verify.net',
            'verified_country_am' => 'Հայաստանի Հանրապետություն',
            'verified_subtitle_am' => 'պաշտոնական փաստաթղթերի վավերականության ստուգման միասնական համակարգ',
        ]);
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'verified_service_name_ru',
                'verified_country_ru',
                'verified_subtitle_ru',
                'verified_service_name_en',
                'verified_country_en',
                'verified_subtitle_en',
                'verified_service_name_am',
                'verified_country_am',
                'verified_subtitle_am',
            ]);
        });
    }
};
