<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'years_of_experience',
        'status',
        'notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'driving_license_expiry' => 'date',
        'years_of_experience' => 'integer'
    ];

    // Définir les statuts possibles
    const STATUS_NOUVEAU = 'nouveau';
    const STATUS_CONTACTE = 'contacte';
    const STATUS_ENTRETIEN = 'entretien';
    const STATUS_TEST = 'test';
    const STATUS_OFFRE = 'offre';
    const STATUS_EMBAUCHE = 'embauche';
    const STATUS_REFUSE = 'refuse';
    const STATUS_EN_COURS = 'en cours';

    // Liste des statuts possibles
    public static $statuses = [
        self::STATUS_NOUVEAU => 'Nouveau',
        self::STATUS_CONTACTE => 'Contacté',
        self::STATUS_ENTRETIEN => 'Entretien',
        self::STATUS_TEST => 'Test',
        self::STATUS_OFFRE => 'Offre',
        self::STATUS_EMBAUCHE => 'Embauché',
        self::STATUS_REFUSE => 'Refusé',
        self::STATUS_EN_COURS => 'En Cours'
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
        return $this->hasMany(Interview::class); 
    }
    public function evaluations(): HasMany { return $this->hasMany(Evaluation::class, 'evaluator_id'); }
    public function drivingTests(): HasMany { return $this->hasMany(DrivingTest::class); }
    public function offers(): HasMany { return $this->hasMany(Offer::class, 'candidate_id'); }
 
    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }

    // Méthodes utilitaires pour vérifier le statut
    public function isNouveau(): bool
    {
        return $this->status === self::STATUS_NOUVEAU;
    }

    public function isContacte(): bool
    {
        return $this->status === self::STATUS_CONTACTE;
    }

    public function isEntretien(): bool
    {
        return $this->status === self::STATUS_ENTRETIEN;
    }

    public function isTest(): bool
    {
        return $this->status === self::STATUS_TEST;
    }

    public function isOffre(): bool
    {
        return $this->status === self::STATUS_OFFRE;
    }

    public function isEmbauche(): bool
    {
        return $this->status === self::STATUS_EMBAUCHE;
    }

    public function isRefuse(): bool
    {
        return $this->status === self::STATUS_REFUSE;
    }

    // Obtenir le nom complet
    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Vérifier si le permis expire bientôt (dans les 60 jours)
    public function hasExpiringLicense(): bool
    {
        if (!$this->driving_license_expiry) {
            return false;
        }
        return $this->driving_license_expiry->diffInDays(now()) <= 60;
    }
}