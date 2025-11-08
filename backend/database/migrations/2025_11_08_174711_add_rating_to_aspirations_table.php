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
        Schema::table('aspirations', function (Blueprint $table) {
            // Rating bintang (1-5)
            // Kita buat nullable() agar data lama/anonim tidak error
            // Tipe 'unsignedTinyInteger' adalah yang paling efisien (0-255)
            $table->unsignedTinyInteger('rating')->nullable()->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aspirations', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
};