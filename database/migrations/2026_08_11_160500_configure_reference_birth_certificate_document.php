<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('documents')
            ->where('tracking_number', 'CRH9-QS59-I3LN-102T')
            ->update([
                'document_kind' => 'birth_certificate',
                'status' => 'valid',
                'citizen_first_name' => 'ԷՐԻԿ',
                'citizen_patronymic' => 'ՍԵՐՅՈԺԱ',
                'citizen_last_name' => 'ՇՀՈՅԱՆ',
                'citizen_nationality' => 'ՀԱՅ',
                'citizen_citizenship' => null,
                'birth_date' => '2022-10-07',
                'birth_place' => 'ՀՀ, ԳԵՂԱՐՔՈՒՆԻՔ, ԳԱՎԱՌ',
                'father_first_name' => 'ՍԵՐՅՈԺԱ',
                'father_patronymic' => 'ՊԱՎԼԻԿ',
                'father_last_name' => 'ՇՀՈՅԱՆ',
                'father_nationality' => 'ՀԱՅ',
                'mother_first_name' => 'ՌՈՒԶԱՆՆԱ',
                'mother_patronymic' => 'ՀԱԿՈԲԻԿ',
                'mother_last_name' => 'ԲԱՏԻԿՅԱՆ',
                'mother_nationality' => 'ՀԱՅ',
                'registration_date' => '2022-10-11',
                'registration_number' => '987/280/2022',
                'registration_authority' => 'ԳԱՎԱՌԻ ՔԿԱԳ ՏԱՐԱԾՔԱՅԻՆ ԲԱԺԻՆ',
                'certificate_number' => 'ԲԱ256705',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('documents')
            ->where('tracking_number', 'CRH9-QS59-I3LN-102T')
            ->update([
                'document_kind' => 'generic',
                'updated_at' => now(),
            ]);
    }
};