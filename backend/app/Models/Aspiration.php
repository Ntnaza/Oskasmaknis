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
}