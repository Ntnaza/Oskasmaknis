<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Kita ubah nama kolom 'gallery' menjadi 'gallery_files'
            // Agar sesuai dengan kodingan Controller dan Model baru
            if (Schema::hasColumn('articles', 'gallery')) {
                $table->renameColumn('gallery', 'gallery_files');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Kembalikan nama jika rollback
            if (Schema::hasColumn('articles', 'gallery_files')) {
                $table->renameColumn('gallery_files', 'gallery');
            }
        });
    }
};