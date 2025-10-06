<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Impor class HasMany

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'photo_path',
        'social_links',
        'order',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    /**
     * Definisikan relasi one-to-many: Satu anggota tim punya banyak program kerja.
     */
    public function workPrograms(): HasMany
    {
        return $this->hasMany(WorkProgram::class);
    }
}

