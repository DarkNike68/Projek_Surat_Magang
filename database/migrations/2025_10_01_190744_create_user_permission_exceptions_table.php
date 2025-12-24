<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_permission_exceptions', function (Blueprint $table) {
            $table->id();
            // Kunci asing ke tabel 'users' dan 'categories'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            // Kolom izin yang nullable. Null berarti "ikuti aturan unit".
            // True berarti "diizinkan", False berarti "dilarang".
            $table->boolean('can_read')->nullable();
            $table->boolean('can_write')->nullable();

            $table->timestamps();

            // Pastikan tidak ada duplikat pengecualian
            $table->unique(['user_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permission_exceptions');
    }
};