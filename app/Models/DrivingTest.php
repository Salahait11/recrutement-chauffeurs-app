<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'score',
        'passed',
        'results_summary'
    ];

    // Constantes de statut
    public const STATUS_PLANIFIE = 'planifie';
    public const STATUS_REUSSI = 'reussi';
    public const STATUS_ECHOUE = 'echoue';
    public const STATUS_ANNULE = 'annule';

    protected $casts = [
        'test_date' => 'datetime',
        'passed' => 'boolean',
    ];

    public function candidate(): BelongsTo 
    { 
        return $this->belongsTo(Candidate::class); 
    }

    public function vehicle(): BelongsTo 
    { 
        return $this->belongsTo(Vehicle::class); 
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PLANIFIE => 'Planifié',
            self::STATUS_REUSSI => 'Réussi',
            self::STATUS_ECHOUE => 'Échoué',
            self::STATUS_ANNULE => 'Annulé',
        ];
    }

    public static function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            self::STATUS_PLANIFIE => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300',
            self::STATUS_REUSSI => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300',
            self::STATUS_ECHOUE => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300',
            self::STATUS_ANNULE => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-300',
            default => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-300',
        };
    }
}