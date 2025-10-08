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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable(); // Ringkasan singkat
            $table->longText('content')->nullable(); // Isi artikel lengkap
            $table->string('featured_image_path')->nullable(); // Gambar utama
            $table->json('gallery')->nullable(); // Untuk menyimpan daftar path gambar galeri
            $table->timestamp('published_at')->nullable(); // Untuk jadwal publish
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
