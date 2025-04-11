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
        'test_date',
        'route_details',
        'status',
        'passed',
        'results_summary',
    ];

    const STATUS_SCHEDULED = 'planifie';
    const STATUS_PASSED = 'reussi';
    const STATUS_FAILED = 'echoue';
    const STATUS_CANCELED = 'annule';

    protected $casts = [
        'test_date' => 'datetime',
        'passed' => 'boolean',
    ];

    public function candidate(): BelongsTo { return $this->belongsTo(Candidate::class); }
    public function evaluator(): BelongsTo { return $this->belongsTo(User::class, 'evaluator_id'); }
    public function vehicle(): BelongsTo { return $this->belongsTo(Vehicle::class); }

       public static function getVehicleTypes(): array
       {
           return [
               'Voiture',
               'Camion',
               'Moto',
           ];
       }

}