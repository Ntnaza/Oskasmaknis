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
        Schema::create('angkatans', function (Blueprint $table) {
            $table->id();
            
            // Cth: "JIVA ABISATYA"
            $table->string('name'); 
            
            // Cth: "2024/2025"
            $table->string('year_period'); 
            
            // Penanda angkatan mana yang sedang aktif menjabat
            // Kita beri default 'false'
            $table->boolean('is_active')->default(false); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angkatans');
    }
};