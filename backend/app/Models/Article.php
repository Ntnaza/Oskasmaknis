<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // <-- 1. PASTIKAN IMPORT INI ADA

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
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
     */
    protected $casts = [
        'gallery' => 'array',
        'published_at' => 'datetime',
    ];
    
    // ======================================================
    // TAMBAHAN BARU UNTUK URL GAMBAR OTOMATIS
    // ======================================================

    /**
     * Menambahkan atribut 'image_url' ke model secara otomatis saat diubah ke JSON.
     */
    protected $appends = ['image_url'];

    /**
     * Accessor (fungsi 'get') untuk membuat atribut 'image_url'.
     * Fungsi ini akan dipanggil secara otomatis.
     */
    public function getImageUrlAttribute()
    {
        // Cek apakah ada path gambar yang tersimpan
        if ($this->featured_image_path) {
            // Jika ada, buat URL lengkapnya menggunakan helper 'Storage::url()'
            return Storage::url($this->featured_image_path);
        }
        
        // Jika tidak ada gambar, kembalikan null
        return null;
    }

    // ======================================================

    /**
     * Mengubah kunci pencarian default untuk route model binding.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}