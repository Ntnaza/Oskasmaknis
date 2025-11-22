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
        Schema::table('angkatans', function (Blueprint $table) {
            // Kolom untuk menyimpan path gambar background kartu per angkatan
            $table->string('card_background_path')->nullable()->after('theme_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angkatans', function (Blueprint $table) {
            $table->dropColumn('card_background_path');
        });
    }
};