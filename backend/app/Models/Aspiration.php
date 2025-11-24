<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspiration extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     */
    protected $fillable = [
        'angkatan_id', // <--- WAJIB ADA (KUNCI UTAMA FITUR INI)
        'ticket_id',
        'status',
        'name',
        'contact',
        'category',
        'subject',
        'message',
        'rating',
        'file_path',
        'internal_notes',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke Angkatan
     * Berguna jika nanti ingin menampilkan: "Aspirasi ini untuk Angkatan 2024"
     */
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }
}