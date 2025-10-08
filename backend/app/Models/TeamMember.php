<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'position',
        'photo_path',
        'header_photo_path',
        'social_links',
        'bio_data',
        'order',
    ];

    /**
     * The attributes that should be cast.
     * Atribut ini secara otomatis mengubah data dari JSON di database
     * menjadi array PHP saat diakses, dan sebaliknya.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Contoh: ['instagram' => 'url', 'facebook' => 'url']
        'social_links' => 'array',

        // PENAMBAHAN: Dokumentasi struktur untuk bio_data
        // Contoh: [
        //   'sambutan' => '...',
        //   'visi_misi' => '...',
        //   'prestasi' => ['...', '...']
        // ]
        'bio_data' => 'array',
    ];

    /**
     * Mendefinisikan relasi ke Program Kerja.
     */
    public function workPrograms(): HasMany
    {
        return $this->hasMany(WorkProgram::class);
    }
}

