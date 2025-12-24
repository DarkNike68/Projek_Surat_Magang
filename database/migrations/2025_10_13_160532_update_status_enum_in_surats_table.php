<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN INI

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah sementara kolom status menjadi VARCHAR agar bisa menerima nilai apa pun
        Schema::table('surats', function (Blueprint $table) {
            $table->string('status')->change();
        });

        // 2. Jalankan query UPDATE untuk membersihkan data lama
        DB::statement("UPDATE surats SET status = 'Revisi' WHERE status = 'Ditolak atau direvisi'");
        DB::statement("UPDATE surats SET status = 'Disahkan' WHERE status = 'Tervalidasi'");
        DB::statement("UPDATE surats SET status = 'Menunggu Persetujuann' WHERE status IN ('Belum upload', 'Diproses', 'Draf Disetujui', 'Menunggu Verifikasi Final')");
        
        // 3. Kembalikan kolom status ke tipe ENUM dengan daftar nilai yang baru
        Schema::table('surats', function (Blueprint $table) {
            $table->enum('status', [
                'Menunggu Persetujuann',
                'Revisi',
                'Disahkan',
                'Diarsipkan'
            ])->default('Menunggu Persetujuann')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Proses kebalikan jika Anda perlu rollback
        Schema::table('surats', function (Blueprint $table) {
            $table->string('status')->change();
        });

        DB::statement("UPDATE surats SET status = 'Ditolak atau direvisi' WHERE status = 'Revisi'");
        DB::statement("UPDATE surats SET status = 'Tervalidasi' WHERE status = 'Disahkan'");
        DB::statement("UPDATE surats SET status = 'Diproses' WHERE status = 'Menunggu Persetujuann'");

        Schema::table('surats', function (Blueprint $table) {
            $table->enum('status', [
                'Belum upload', 'Diproses', 'Draf Disetujui', 'Menunggu Verifikasi Final',
                'Tervalidasi', 'Ditolak atau direvisi', 'Diarsipkan'
            ])->default('Belum upload')->change();
        });
    }
};