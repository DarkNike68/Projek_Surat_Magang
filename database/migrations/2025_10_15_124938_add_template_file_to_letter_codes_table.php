<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letter_codes', function (Blueprint $table) {
            // Tambahkan kolom untuk menyimpan nama file template setelah kolom 'code'
            $table->string('template_file')->nullable()->after('code');
        });
    }

    public function down(): void
    {
        Schema::table('letter_codes', function (Blueprint $table) {
            $table->dropColumn('template_file');
        });
    }
};