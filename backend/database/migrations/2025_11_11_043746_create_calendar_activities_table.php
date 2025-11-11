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
        Schema::create('calendar_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Cth: "PORSENI" atau "Upacara Bendera"
            
            // Kategori untuk filtering di frontend
            $table->string('category'); // Cth: "Event Sekolah", "Upacara", "Lomba"
            
            // Tanggal & Waktu Mulai
            $table->dateTime('start_date');
            
            // Tanggal & Waktu Selesai (nullable jika acaranya cuma 1 hari)
            $table->dateTime('end_date')->nullable(); 
            
            // Deskripsi (untuk info tambahan, cth: "Petugas: Budi, Ani, Udin")
            $table->text('description')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_activities');
    }
};