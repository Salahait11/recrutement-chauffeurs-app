<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Ajoute cette ligne
use Illuminate\Database\Eloquent\Relations\HasOne;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'birth_date',
        'driving_license_number',
        'driving_license_expiry',
        'status',
        'notes',
    ];

    /**
     * Récupère les documents associés à ce candidat.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class); // Ajoute cette méthode
    }
    public function evaluations(): HasMany { return $this->hasMany(Evaluation::class, 'evaluator_id'); }
    public function drivingTests(): HasMany { return $this->hasMany(DrivingTest::class); }
    public function offers(): HasMany { return $this->hasMany(Offer::class); }
 
public function employee(): HasOne { return $this->hasOne(Employee::class); }
    // Nous ajouterons les autres relations (interviews, etc.) ici plus tard
}