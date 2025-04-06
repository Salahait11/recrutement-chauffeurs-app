<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Ajoute cette ligne

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'description',
        'expiry_date',
    ];

    /**
     * Récupère le candidat auquel ce document appartient.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}