<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // --- TAMBAHKAN BARIS-BARIS INI ---
    protected $fillable = [
        'event_id',
        'team_member_id',
        'attended_at',
    ];

    /**
     * Kita tidak pakai 'updated_at' di tabel absensi.
     */
    public const UPDATED_AT = null;
    // --- BATAS TAMBAHAN ---

    // (Opsional, tapi bagus) Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // (Opsional, tapi bagus) Relasi ke Anggota
    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class);
    }
}