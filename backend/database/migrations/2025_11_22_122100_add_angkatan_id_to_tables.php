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
        // 1. Untuk Tabel Program Kerja
        Schema::table('work_programs', function (Blueprint $table) {
            if (!Schema::hasColumn('work_programs', 'angkatan_id')) {
                $table->foreignId('angkatan_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('angkatans')
                      ->onDelete('set null');
            }
        });

        // 2. Untuk Tabel Artikel
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'angkatan_id')) {
                $table->foreignId('angkatan_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('angkatans')
                      ->onDelete('set null');
            }
        });

        // 3. Untuk Tabel Kalender
        Schema::table('calendar_activities', function (Blueprint $table) {
             if (!Schema::hasColumn('calendar_activities', 'angkatan_id')) {
                $table->foreignId('angkatan_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('angkatans')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_programs', function (Blueprint $table) {
            $table->dropForeign(['angkatan_id']);
            $table->dropColumn('angkatan_id');
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['angkatan_id']);
            $table->dropColumn('angkatan_id');
        });
         Schema::table('calendar_activities', function (Blueprint $table) {
            $table->dropForeign(['angkatan_id']);
            $table->dropColumn('angkatan_id');
        });
    }
};