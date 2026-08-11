<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->string('about_image')->nullable();

            foreach (['ru', 'en', 'am'] as $locale) {
                $table->string("about_title_{$locale}")->nullable();
                $table->text("about_intro_{$locale}")->nullable();
                $table->json("about_items_{$locale}")->nullable();
                $table->string("about_seo_title_{$locale}")->nullable();
                $table->text("about_seo_description_{$locale}")->nullable();

                $table->string("statistics_title_{$locale}")->nullable();
                $table->text("statistics_intro_{$locale}")->nullable();
                $table->json("statistics_items_{$locale}")->nullable();
                $table->string("statistics_seo_title_{$locale}")->nullable();
                $table->text("statistics_seo_description_{$locale}")->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $columns = ['about_image'];

            foreach (['ru', 'en', 'am'] as $locale) {
                array_push(
                    $columns,
                    "about_title_{$locale}",
                    "about_intro_{$locale}",
                    "about_items_{$locale}",
                    "about_seo_title_{$locale}",
                    "about_seo_description_{$locale}",
                    "statistics_title_{$locale}",
                    "statistics_intro_{$locale}",
                    "statistics_items_{$locale}",
                    "statistics_seo_title_{$locale}",
                    "statistics_seo_description_{$locale}",
                );
            }

            $table->dropColumn($columns);
        });
    }
};
