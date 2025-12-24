<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus foreign key dari tabel dengan NAMA ASLI mereka
        Schema::table('raks', function (Blueprint $table) {
            $table->dropForeign(['lemari_id']);
        });
        Schema::table('outners', function (Blueprint $table) {
            $table->dropForeign(['rak_id']);
        });

        // 2. Ubah nama tabel dengan urutan yang benar
        Schema::rename('raks', 'skats');
        Schema::rename('lemaris', 'raks');

        // 3. Ubah nama kolom pada tabel yang SUDAH diubah namanya
        Schema::table('skats', function (Blueprint $table) {
            $table->renameColumn('lemari_id', 'rak_id');
        });
        Schema::table('outners', function (Blueprint $table) {
            $table->renameColumn('rak_id', 'skat_id');
        });

        // 4. Buat kembali foreign key dengan referensi yang baru
        Schema::table('skats', function (Blueprint $table) {
            $table->foreign('rak_id')->references('id')->on('raks')->onDelete('cascade');
        });
        Schema::table('outners', function (Blueprint $table) {
            $table->foreign('skat_id')->references('id')->on('skats')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // 1. Hapus foreign key baru
        Schema::table('skats', function (Blueprint $table) {
            $table->dropForeign(['rak_id']);
        });
        Schema::table('outners', function (Blueprint $table) {
            $table->dropForeign(['skat_id']);
        });

        // 2. Ubah nama kolom kembali ke nama lama
        Schema::table('skats', function (Blueprint $table) {
            $table->renameColumn('rak_id', 'lemari_id');
        });
        Schema::table('outners', function (Blueprint $table) {
            $table->renameColumn('skat_id', 'rak_id');
        });

        // 3. Ubah nama tabel kembali ke nama lama (urutan dibalik)
        Schema::rename('raks', 'lemaris');
        Schema::rename('skats', 'raks');
        
        // 4. Buat kembali foreign key yang lama
        Schema::table('raks', function (Blueprint $table) {
            $table->foreign('lemari_id')->references('id')->on('lemaris')->onDelete('cascade');
        });
        Schema::table('outners', function (Blueprint $table) {
            $table->foreign('rak_id')->references('id')->on('raks')->onDelete('cascade');
        });
    }
};