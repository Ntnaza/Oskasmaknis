<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Impor class BelongsTo

class WorkProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'angkatan_id',
        'team_member_id',
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
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }
}

