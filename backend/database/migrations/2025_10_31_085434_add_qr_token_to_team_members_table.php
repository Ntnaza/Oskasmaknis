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
            // Tambahkan kolom ini setelah kolom 'id' (atau di mana saja)
            // Ini akan jadi isi QR code permanen anggota
            $table->string('member_token')
                  ->unique()       // WAJIB: Setiap token harus unik
                  ->nullable()     // Boleh null, karena kita akan generate nanti
                  ->after('id');  // Opsional: menempatkan kolomnya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            // Ini untuk 'rollback' jika diperlukan
            $table->dropColumn('member_token');
        });
    }
};