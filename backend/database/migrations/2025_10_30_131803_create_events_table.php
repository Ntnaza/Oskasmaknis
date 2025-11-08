<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul kegiatan (Sesuai kode Anda)
            
            // SARAN: Gunakan dateTime, bukan hanya date.
            // Kenapa? Agar kita bisa atur absensi buka jam berapa.
            $table->dateTime('event_date'); // Tanggal & Jam kegiatan
            
            $table->text('description')->nullable(); // Deskripsi (Sesuai kode Anda)
            
            // --- PENAMBAHAN UNTUK FITUR QR ---
            // Kolom untuk menyimpan kode unik (cth: "R4P4T-PL3N0-XYZ")
            $table->string('qr_token')->unique()->nullable();
            
            // Status: 'pending', 'active', 'finished'
            $table->string('status')->default('pending'); 
            // --- BATAS PENAMBAHAN ---
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};