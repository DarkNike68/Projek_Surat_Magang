<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perintah untuk memodifikasi tabel 'users'
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom baru 'unit_id' setelah kolom 'id'
            // Kolom ini adalah foreign key yang terhubung ke tabel 'units'
            $table->foreignId('unit_id')->nullable()->after('id')->constrained('units')->onDelete('set null');
            // nullable(): Boleh kosong.
            // constrained('units'): Terhubung ke tabel 'units'.
            // onDelete('set null'): Jika unit dihapus, isi kolom ini menjadi NULL.
        });
    }

    public function down(): void
    {
        // Perintah untuk membatalkan perubahan jika migrasi di-rollback
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['unit_id']);
            // Hapus kolom 'unit_id'
            $table->dropColumn('unit_id');
        });
    }
};