<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

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
    ];

    public static function getStatuses()
    {
        return [
            'planifié',
            'terminé',
            'annulé',
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



}