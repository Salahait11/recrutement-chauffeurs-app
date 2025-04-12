<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <<< VÉRIFIE CET IMPORT

class DrivingTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'evaluator_id',
        'vehicle_id',
        'notes',
        'test_date',
        'status',
        'feedback',
    ];

    const STATUS_SCHEDULED = 'planifié';
    const STATUS_PASSED = 'réussi';
    const STATUS_FAILED = 'échoué';
    const STATUS_CANCELED = 'annulé';

    protected $casts = [
        'test_date' => 'datetime',
    ];

    public function candidate(): BelongsTo { return $this->belongsTo(Candidate::class); }
    public function vehicle(): BelongsTo { return $this->belongsTo(Vehicle::class); }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}