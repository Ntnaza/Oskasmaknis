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
    Schema::create('work_programs', function (Blueprint $table) {
        $table->id();
        
        // KOLOM KEPEMILIKAN (RELASI)
        $table->foreignId('team_member_id')->constrained()->onDelete('cascade');

        $table->string('title');
        $table->text('description');
        $table->string('status')->default('Direncanakan');
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_programs');
    }
};
