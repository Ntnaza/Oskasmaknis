<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('features', function (Blueprint $table) {
        $table->id();
        $table->string('icon');
        // HANYA HAPUS ->after('icon') DARI BARIS INI
        $table->string('color')->default('red'); 
        $table->string('title');
        $table->text('description');
        $table->integer('order')->default(0);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};