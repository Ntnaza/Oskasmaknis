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
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel events
        $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
        // Relasi ke tabel anggota (kamu pakai 'team_members' kan?)
        $table->foreignId('team_member_id')->constrained('team_members')->onDelete('cascade');
        $table->timestamp('attended_at'); // Waktu scan QR
        $table->timestamps();

        // Mencegah 1 orang absen 2x di event yang sama
        $table->unique(['event_id', 'team_member_id']); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
