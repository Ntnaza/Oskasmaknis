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
        Schema::table('content_blocks', function (Blueprint $table) {
            if (!Schema::hasColumn('content_blocks', 'angkatan_id')) {
                $table->foreignId('angkatan_id')
                      ->nullable()
                      // ->after('key')  <-- KITA HAPUS BAGIAN INI
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
        Schema::table('content_blocks', function (Blueprint $table) {
            if (Schema::hasColumn('content_blocks', 'angkatan_id')) {
                $table->dropForeign(['angkatan_id']);
                $table->dropColumn('angkatan_id');
            }
        });
    }
};