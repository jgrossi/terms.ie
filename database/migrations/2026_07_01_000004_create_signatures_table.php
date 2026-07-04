<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('client_id', 30)->index();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->string('term_version_id', 30)->index();
            $table->foreign('term_version_id')->references('id')->on('term_versions')->restrictOnDelete();
            $table->string('status', 20)->default('pending');
            $table->json('variables')->default('{}');
            $table->string('signed_name')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('signed_ip', 45)->nullable();
            $table->string('content_hash')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
