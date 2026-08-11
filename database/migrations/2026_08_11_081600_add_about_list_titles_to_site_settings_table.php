<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            foreach (['ru', 'en', 'am'] as $locale) {
                $table->string("about_list_title_{$locale}")->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'about_list_title_ru',
                'about_list_title_en',
                'about_list_title_am',
            ]);
        });
    }
};
