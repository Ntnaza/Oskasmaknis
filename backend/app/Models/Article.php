<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'angkatan_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_path',
        'gallery_files',
        'published_at',
    ];

    protected $casts = [
        'gallery_files' => 'array', // Otomatis convert JSON ke Array
        'published_at' => 'datetime',
    ];
    
    protected $appends = ['image_url', 'gallery_urls'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            $article->slug = Str::slug($article->title);
        });
        static::updating(function ($article) {
            $article->slug = Str::slug($article->title);
        });
    }

    // --- ACCESSOR YANG LEBIH AMAN (SAFE) ---

    public function getImageUrlAttribute()
    {
        if ($this->featured_image_path) {
            return url(Storage::url($this->featured_image_path));
        }
        return null;
    }

    public function getGalleryUrlsAttribute()
    {
        // PENCEGAHAN ERROR 500:
        // Cek apakah gallery_files ada DAN benar-benar array
        if (empty($this->gallery_files) || !is_array($this->gallery_files)) {
            return []; 
        }
        
        // Jika data valid, baru diproses
        return array_map(function($path) {
            // Cek jika $path bukan string (jaga-jaga)
            if (!is_string($path)) return ''; 
            return url(Storage::url($path));
        }, $this->gallery_files);
    }

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}