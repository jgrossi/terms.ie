<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('term_id')->index();
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->longText('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_versions');
    }
};
