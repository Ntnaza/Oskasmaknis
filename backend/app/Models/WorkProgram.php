<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Impor class BelongsTo

class WorkProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_member_id', // <-- Tambahkan ini
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
    ];

    /**
     * Definisikan relasi inverse one-to-one/many: Satu proker dimiliki oleh satu anggota tim.
     */
    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class);
    }
}

