<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_permissions', function (Blueprint $table) {
            $table->id();
            // Kunci asing ke tabel 'units' dan 'categories'
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            // Kolom untuk izin baca dan tulis (Read & Write)
            $table->boolean('can_read')->default(false);
            $table->boolean('can_write')->default(false);

            $table->timestamps();

            // Pastikan tidak ada duplikat izin untuk kombinasi unit dan kategori yang sama
            $table->unique(['unit_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_permissions');
    }
};