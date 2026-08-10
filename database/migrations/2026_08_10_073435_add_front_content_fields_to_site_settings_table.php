<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            foreach (['ru', 'en', 'am'] as $locale) {
                $table->string("nav_about_{$locale}")->nullable();
                $table->string("nav_statistics_{$locale}")->nullable();
                $table->string("hero_subtitle_{$locale}")->nullable();
                $table->string("date_placeholder_{$locale}")->nullable();
            }

            $table->string('footer_left_image')->nullable();
            $table->string('footer_right_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            foreach (['ru', 'en', 'am'] as $locale) {
                $table->dropColumn([
                    "nav_about_{$locale}",
                    "nav_statistics_{$locale}",
                    "hero_subtitle_{$locale}",
                    "date_placeholder_{$locale}",
                ]);
            }

            $table->dropColumn([
                'footer_left_image',
                'footer_right_image',
            ]);
        });
    }
};
