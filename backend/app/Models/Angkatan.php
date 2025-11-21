<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'name',
        'year_period',
        'is_active',
        'theme_color',
    ];

    /**
     * Tipe data (casting)
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
}