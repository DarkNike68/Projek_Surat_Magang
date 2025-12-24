<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {

            $table->enum('status', [
                'Belum upload', 
                'Menunggu Persetujuan',
                'Revisi',
                'Disahkan',
                'Diarsipkan'
            ])->default('Belum upload')->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {

            $table->enum('status', [
                'Menunggu Persetujuan',
                'Revisi',
                'Disahkan',
                'Diarsipkan'
            ])->default('Menunggu Persetujuan')->change();
        });
    }
};