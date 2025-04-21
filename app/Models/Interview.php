php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'scheduler_id',
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

}