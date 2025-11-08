<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * (Kita tambahkan 'status' dan 'qr_token')
     */
    protected $fillable = [
        'title',
        'event_date',
        'description',
        'status', 
        'qr_token', // <-- TAMBAHKAN BARIS INI
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'event_date' => 'datetime', 
    ];

    // ... (sisa method relasi attendances() dan attendees() Anda) ...
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function attendees()
    {
        return $this->hasManyThrough(
            TeamMember::class, 
            Attendance::class, 
            'event_id',     
            'id',           
            'id',           
            'team_member_id' 
        );
    }
}