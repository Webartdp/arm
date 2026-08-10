<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('hero_image')->nullable();

            $table->string('footer_email')->nullable();

            foreach (['ru', 'en', 'am'] as $locale) {
                $table->string("site_name_{$locale}")->nullable();
                $table->string("hero_title_{$locale}")->nullable();

                $table->string("form_title_{$locale}")->nullable();
                $table->string("input_placeholder_{$locale}")->nullable();
                $table->string("button_text_{$locale}")->nullable();

                $table->text("helper_text_{$locale}")->nullable();

                $table->string("footer_title_{$locale}")->nullable();
                $table->text("footer_address_{$locale}")->nullable();
                $table->string("copyright_{$locale}")->nullable();

                $table->string("seo_title_{$locale}")->nullable();
                $table->text("seo_description_{$locale}")->nullable();
            }

            $table->timestamps();
        });

        DB::table('site_settings')->insert([
            'id' => 1,

            'site_name_ru' => 'Система проверки документов',
            'hero_title_ru' => 'Проверка подлинности документов',
            'form_title_ru' => 'Введите номер документа',
            'input_placeholder_ru' => 'XXXX-XXXX-XXXX-XXXX',
            'button_text_ru' => 'Проверить',
            'helper_text_ru' => 'Для проверки документа введите 16-значный код, указанный на документе.',
            'footer_title_ru' => 'Служба проверки документов',
            'footer_address_ru' => 'Контактная информация',
            'copyright_ru' => 'Все права защищены',
            'seo_title_ru' => 'Проверка документов',
            'seo_description_ru' => 'Онлайн-система проверки подлинности документов.',

            'site_name_en' => 'Document Verification System',
            'hero_title_en' => 'Document authenticity verification',
            'form_title_en' => 'Enter the document tracking number',
            'input_placeholder_en' => 'XXXX-XXXX-XXXX-XXXX',
            'button_text_en' => 'Verify',
            'helper_text_en' => 'Enter the 16-character verification code shown on the document.',
            'footer_title_en' => 'Document Verification Service',
            'footer_address_en' => 'Contact information',
            'copyright_en' => 'All rights reserved',
            'seo_title_en' => 'Document Verification',
            'seo_description_en' => 'Online document authenticity verification system.',

            'site_name_am' => 'Փաստաթղթերի ստուգման համակարգ',
            'hero_title_am' => 'Փաստաթղթերի իսկության ստուգում',
            'form_title_am' => 'Մուտքագրեք փաստաթղթի համարը',
            'input_placeholder_am' => 'XXXX-XXXX-XXXX-XXXX',
            'button_text_am' => 'Ստուգել',
            'helper_text_am' => 'Փաստաթուղթը ստուգելու համար մուտքագրեք փաստաթղթում նշված 16-նիշ կոդը։',
            'footer_title_am' => 'Փաստաթղթերի ստուգման ծառայություն',
            'footer_address_am' => 'Կոնտակտային տվյալներ',
            'copyright_am' => 'Բոլոր իրավունքները պաշտպանված են',
            'seo_title_am' => 'Փաստաթղթերի ստուգում',
            'seo_description_am' => 'Փաստաթղթերի իսկության առցանց ստուգման համակարգ։',

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
