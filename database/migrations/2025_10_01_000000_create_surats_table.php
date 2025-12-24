<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->integer('nomor_urut_per_tahun');
            $table->year('tahun');
            $table->string('bulan_romawi');
            $table->foreignId('letter_code_jenis_surat_id')->constrained('letter_codes');
            $table->foreignId('letter_code_jabatan_id')->constrained('letter_codes');
            $table->string('perihal')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->string('file_path')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', [
                'Belum upload', 'Diproses', 'Draf Disetujui', 'Menunggu Verifikasi Final',
                'Tervalidasi', 'Ditolak atau direvisi', 'Diarsipkan'
            ])->default('Belum upload');
            $table->foreignId('outner_id')->nullable()->constrained('outners')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
