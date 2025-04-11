<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends Model
{
    use HasFactory;

    // Constantes pour les statuts d'entretien
    const STATUS_PLANIFIE = 'planifié';
    const STATUS_TERMINE = 'terminé';
    const STATUS_ANNULE = 'annulé';

    // Constantes pour les types d'entretien
    const TYPE_INITIAL = 'initial';
    const TYPE_TECHNIQUE = 'technique';
    const TYPE_FINAL = 'final';

    /**
     * Liste des statuts disponibles
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PLANIFIE,
            self::STATUS_TERMINE,
            self::STATUS_ANNULE,
        ];
    }

    /**
     * Liste des types d'entretien disponibles
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_INITIAL,
            self::TYPE_TECHNIQUE,
            self::TYPE_FINAL,
        ];
    }

    protected $fillable = [
        'candidate_id',
        'scheduler_id',
        'interviewer_id',
        'interview_date',
        'type',
        'location',
        'status',
        'notes',
        'feedback',
    ];

    /**
     * Les attributs qui doivent être castés.
     * Utile pour s'assurer que interview_date est un objet Carbon.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'interview_date' => 'datetime',
    ];

    /**
     * Récupère le candidat concerné par l'entretien.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Récupère l'utilisateur qui a planifié l'entretien.
     */
    public function scheduler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduler_id');
    }

    /**
     * Récupère l'intervieweur principal.
     */
    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
    public function evaluations(): HasMany { return $this->hasMany(Evaluation::class); }
    

     // Relation vers les évaluations (à ajouter plus tard)
    // public function evaluations(): HasMany
    // {
    //     return $this->hasMany(Evaluation::class);
    // }
}