<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Tambahkan kolom boolean baru setelah kolom 'status'
            // Defaultnya adalah 'false' (0), artinya tidak disembunyikan
            $table->boolean('is_hidden_from_history')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn('is_hidden_from_history');
        });
    }
};