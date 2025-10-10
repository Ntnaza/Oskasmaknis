<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_path',
        'gallery',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gallery' => 'array', // Memberitahu Laravel bahwa kolom ini adalah JSON/array
        'published_at' => 'datetime', // Memberitahu Laravel bahwa kolom ini adalah tanggal
    ];
    /**
     * Mengubah kunci pencarian default untuk route model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug'; // <-- TAMBAHKAN FUNGSI INI
    }
}
