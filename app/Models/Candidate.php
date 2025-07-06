<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidate extends Model
{
    use HasFactory;

    const STATUS_LABELS = [
        'nouveau' => 'Nouveau',
        'contacte' => 'Contacté',
        'entretien' => 'En entretien',
        'test' => 'Test',
        'offre' => 'Offre',
        'embauche' => 'Embauché',
        'refuse' => 'Refusé'
    ];

    protected $fillable = [
        'candidate_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'birth_date',
        'cin',
        'marital_status',
        'children_count',
        'driving_license_number',
        'driving_license_obtained_date',
        'driving_license_expiry',
        'years_of_experience',
        'status',
        'notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'driving_license_obtained_date' => 'date',
        'driving_license_expiry' => 'date',
        'years_of_experience' => 'integer',
        'children_count' => 'integer'
    ];

    // Définir les statuts possibles
    const STATUS_NOUVEAU = 'nouveau';
    const STATUS_CONTACTE = 'contacte';
    const STATUS_ENTRETIEN = 'entretien';
    const STATUS_TEST = 'test';
    const STATUS_OFFRE = 'offre';
    const STATUS_EMBAUCHE = 'embauche';
    const STATUS_REFUSE = 'refuse';

    // Liste des statuts possibles
    public static $statuses = [
        self::STATUS_NOUVEAU => 'Nouveau',
        self::STATUS_CONTACTE => 'Contacté',
        self::STATUS_ENTRETIEN => 'Entretien',
        self::STATUS_TEST => 'Test',
        self::STATUS_OFFRE => 'Offre',
        self::STATUS_EMBAUCHE => 'Embauché',
        self::STATUS_REFUSE => 'Refusé',
    ];

    // Constantes pour la situation familiale
    const MARITAL_STATUS_SINGLE = 'single';
    const MARITAL_STATUS_MARRIED = 'married';
    const MARITAL_STATUS_DIVORCED = 'divorced';
    const MARITAL_STATUS_WIDOWED = 'widowed';

    // Liste des situations familiales
    public static $maritalStatuses = [
        self::MARITAL_STATUS_SINGLE => 'Célibataire',
        self::MARITAL_STATUS_MARRIED => 'Marié(e)',
        self::MARITAL_STATUS_DIVORCED => 'Divorcé(e)',
        self::MARITAL_STATUS_WIDOWED => 'Veuf/Veuve',
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

    // Générer automatiquement le numéro de candidat
    public static function generateCandidateNumber(): string
    {
        $year = date('Y');
        $lastCandidate = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        
        if ($lastCandidate && $lastCandidate->candidate_number) {
            // Extraire le numéro séquentiel du dernier candidat
            $lastNumber = (int) substr($lastCandidate->candidate_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return sprintf('CAN%s%04d', $year, $nextNumber);
    }

    // Calculer l'ancienneté du permis en années
    public function getLicenseSeniority(): ?int
    {
        if (!$this->driving_license_obtained_date) {
            return null;
        }
        return $this->driving_license_obtained_date->diffInYears(now());
    }

    // Obtenir le label de la situation familiale
    public function getMaritalStatusLabel(): ?string
    {
        return self::$maritalStatuses[$this->marital_status] ?? null;
    }

    // Boot method pour générer automatiquement le numéro de candidat
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($candidate) {
            if (empty($candidate->candidate_number)) {
                $candidate->candidate_number = self::generateCandidateNumber();
            }
        });
    }
}