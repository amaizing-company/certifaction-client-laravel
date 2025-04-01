<?php

use AmaizingCompany\CertifactionClient\Support\DatabaseHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(DatabaseHelper::getTableName('file_transactions'), function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('document_id')
                ->constrained(DatabaseHelper::getTableName('documents'))
                ->cascadeOnDelete();
            $table->string('status');
            $table->longText('file_url');
            $table->string('failure_reason')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(DatabaseHelper::getTableName('file_transactions'));
    }
};
