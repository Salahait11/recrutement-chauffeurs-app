<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    // Constantes de statut
    const STATUS_PLANIFIE = 'planifié';
    const STATUS_EN_COURS = 'en cours';
    const STATUS_TERMINE = 'terminé';
    const STATUS_ANNULE = 'annulé';
    const STATUS_EVALUE = 'évalué';

    protected $fillable = [
        'candidate_id',
        'scheduler_id',
        'interviewer_id',
        'interview_date',
        'type',
        'notes',
        'status',
        'result',
        'feedback',
        'duration',
    ];

    protected $casts = [
        'interview_date' => 'datetime',
    ];

    public static function getStatuses()
    {
        return [
            self::STATUS_PLANIFIE,
            self::STATUS_EN_COURS,
            self::STATUS_TERMINE,
            self::STATUS_ANNULE,
            self::STATUS_EVALUE,
        ];
    }

    public static function getTypes()
    {
        return [
            'initial',
            'technique',
            'final',
        ];
    }
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function scheduler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduler_id');
    }
    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }

}