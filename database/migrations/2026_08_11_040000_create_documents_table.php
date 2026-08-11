<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 19)->unique();
            $table->string('document_type')->nullable();
            $table->string('title')->nullable();
            $table->string('subject_name')->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->date('issue_date')->nullable()->index();
            $table->date('valid_until')->nullable()->index();
            $table->string('file_path')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
