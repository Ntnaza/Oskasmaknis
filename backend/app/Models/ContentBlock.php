<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_slug',
        'section_key',
        'content',
        'angkatan_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content' => 'array', // Memberitahu Laravel bahwa kolom 'content' adalah JSON/array
    ];
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }
}