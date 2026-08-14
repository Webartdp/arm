<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table): void {
            $table->string('processing_status', 32)->default('not_processed')->after('file_path');
            $table->text('processing_error')->nullable()->after('processing_status');
            $table->longText('extracted_text')->nullable()->after('processing_error');
            $table->timestamp('processed_at')->nullable()->after('extracted_text');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table): void {
            $table->dropColumn([
                'processing_status',
                'processing_error',
                'extracted_text',
                'processed_at',
            ]);
        });
    }
};
