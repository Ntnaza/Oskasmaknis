<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'card_background_path',
    ];

    /**
     * Tipe data (casting)
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    // --- TAMBAHAN 2: Accessor Otomatis ---
    // Ini akan membuat field 'card_background_url' muncul otomatis di JSON response
    protected $appends = ['card_background_url'];

    public function getCardBackgroundUrlAttribute()
    {
        if ($this->card_background_path) {
            return Storage::url($this->card_background_path);
        }
        return null;
    }
    public function members()
    {
        return $this->hasMany(TeamMember::class, 'angkatan_id');
    }
}