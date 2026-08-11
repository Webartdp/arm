<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('document_kind', 50)->default('generic')->after('tracking_number')->index();

            $table->string('citizen_first_name')->nullable()->after('subject_name');
            $table->string('citizen_patronymic')->nullable()->after('citizen_first_name');
            $table->string('citizen_last_name')->nullable()->after('citizen_patronymic');
            $table->string('citizen_nationality')->nullable()->after('citizen_last_name');
            $table->string('citizen_citizenship')->nullable()->after('citizen_nationality');

            $table->date('birth_date')->nullable()->after('citizen_citizenship');
            $table->string('birth_place')->nullable()->after('birth_date');

            $table->string('father_first_name')->nullable()->after('birth_place');
            $table->string('father_patronymic')->nullable()->after('father_first_name');
            $table->string('father_last_name')->nullable()->after('father_patronymic');
            $table->string('father_nationality')->nullable()->after('father_last_name');

            $table->string('mother_first_name')->nullable()->after('father_nationality');
            $table->string('mother_patronymic')->nullable()->after('mother_first_name');
            $table->string('mother_last_name')->nullable()->after('mother_patronymic');
            $table->string('mother_nationality')->nullable()->after('mother_last_name');

            $table->date('registration_date')->nullable()->after('mother_nationality');
            $table->string('registration_number')->nullable()->after('registration_date');
            $table->string('registration_authority')->nullable()->after('registration_number');
            $table->string('certificate_number')->nullable()->after('registration_authority');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'document_kind',
                'citizen_first_name',
                'citizen_patronymic',
                'citizen_last_name',
                'citizen_nationality',
                'citizen_citizenship',
                'birth_date',
                'birth_place',
                'father_first_name',
                'father_patronymic',
                'father_last_name',
                'father_nationality',
                'mother_first_name',
                'mother_patronymic',
                'mother_last_name',
                'mother_nationality',
                'registration_date',
                'registration_number',
                'registration_authority',
                'certificate_number',
            ]);
        });
    }
};
