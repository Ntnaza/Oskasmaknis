<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarActivity extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi.
     */
    protected $fillable = [
        'angkatan_id', // <--- WAJIB ADA AGAR TIDAK ERROR/NULL
        'title',
        'category',
        'start_date',
        'end_date',
        'description',
    ];

    /**
     * Otomatis cast kolom tanggal
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Relasi ke Angkatan (Opsional, tapi bagus untuk referensi)
     */
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }
}