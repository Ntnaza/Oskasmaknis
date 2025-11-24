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
    public function angkatan()
    {
        // Pastikan kamu sudah punya model Angkatan di App\Models\Angkatan
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    /**
     * Scope: Filter Cepat (Shortcut Query)
     * Gunanya: Supaya di Controller cukup tulis: TeamMember::fromAngkatan($id)->get();
     * Tidak perlu tulis: TeamMember::where('angkatan_id', $id)->get();
     */
    public function scopeFromAngkatan($query, $angkatanId)
    {
        // Jika ID ada, filter. Jika tidak (null), abaikan filter.
        return $query->when($angkatanId, function ($q) use ($angkatanId) {
            return $q->where('angkatan_id', $angkatanId);
        });
    }

    /**
     * Scope: Hanya Ambil Angkatan yang Sedang Aktif (Is Active)
     * Gunanya: Untuk tampilan default Landing Page tanpa user perlu milih tahun.
     * Cara pakai: TeamMember::currentActive()->get();
     */
    public function scopeCurrentActive($query)
    {
        return $query->whereHas('angkatan', function ($q) {
            $q->where('is_active', true);
        });
    }
}