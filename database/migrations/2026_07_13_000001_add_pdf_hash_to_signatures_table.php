<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('signatures', function (Blueprint $table) {
            // SHA-256 (64 hex chars) of the signed PDF file bytes — the value a
            // client's uploaded copy is matched against during verification.
            $table->string('pdf_hash', 64)->nullable()->index()->after('pdf_path');
        });
    }

    public function down(): void
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->dropIndex(['pdf_hash']);
            $table->dropColumn('pdf_hash');
        });
    }
};
