<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Kita tidak perlu BelongsToMany atau Event di sini
// use Illuminate\Database\Eloquent\Relations\BelongsToMany; 
// use App\Models\Event; 

class TeamMember extends Model
{
    use HasFactory;

    // Ini semua dari kode Anda, sudah benar
    protected $fillable = [
        'angkatan_id',
        'name',
        'position',
        'photo_path',
        'header_photo_path',
        'social_links',
        'bio_data',
        'order',
    ];

    // Ini dari kode Anda, sudah benar
    protected $casts = [
        'social_links' => 'array',
        'bio_data' => 'array',
    ];

    // Ini dari kode Anda, sudah benar
    public function workPrograms(): HasMany
    {
        return $this->hasMany(WorkProgram::class);
    }

    
    // --- INI ADALAH PERBAIKANNYA ---

    /**
     * Relasi: Satu Anggota memiliki BANYAK data absensi.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relasi: Satu Anggota bisa menghadiri BANYAK Event
     * (melalui tabel Attendance)
     */
    public function eventsAttended()
    {
        return $this->hasManyThrough(
            Event::class,
            Attendance::class,
            'team_member_id', // Foreign key di tabel perantara (attendances)
            'id',             // Foreign key di tabel tujuan (events)
            'id',             // Local key di tabel ini (team_members)
            'event_id'        // Local key di tabel perantara (attendances)
        );
    }
}