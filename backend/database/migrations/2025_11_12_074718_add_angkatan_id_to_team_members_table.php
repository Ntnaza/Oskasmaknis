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
        Schema::table('team_members', function (Blueprint $table) {
            // 1. Tambahkan kolom 'angkatan_id'
            // Kita buat 'nullable' agar data anggota lama Anda tidak error
            $table->foreignId('angkatan_id')
                  ->nullable()
                  ->constrained('angkatans') // Menghubungkan ke tabel 'angkatans'
                  ->onDelete('set null'); // Jika angkatan dihapus, set ID anggota ini ke NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            // Ini untuk 'rollback'
            $table->dropForeign(['angkatan_id']);
            $table->dropColumn('angkatan_id');
        });
    }
};