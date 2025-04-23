<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model {
    use HasFactory;
    // Ajoute driving_test_id ici
    protected $fillable = [
        'candidate_id',
        'evaluator_id',
        'interview_id',
        'driving_test_id',
        'conclusion',
        'recommendation',
        'overall_rating',
    ];

    // Constantes pour les types d'évaluation
    public const TYPE_INTERVIEW = 'interview';
    public const TYPE_DRIVING_TEST = 'driving_test';

    // Constantes pour les recommandations
    public const RECOMMENDATION_POSITIVE = 'positive';
    public const RECOMMENDATION_NEUTRAL = 'neutral';
    public const RECOMMENDATION_NEGATIVE = 'negative';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Détermine le type d'évaluation
     */
    public function getType(): string
    {
        return $this->interview_id ? self::TYPE_INTERVIEW : self::TYPE_DRIVING_TEST;
    }

    /**
     * Retourne la liste des types d'évaluation disponibles
     */
    public static function getTypesList(): array
    {
        return [
            self::TYPE_INTERVIEW => 'Entretien',
            self::TYPE_DRIVING_TEST => 'Test de conduite',
        ];
    }

    /**
     * Retourne la liste des recommandations disponibles
     */
    public static function getRecommendationsList(): array
    {
        return [
            self::RECOMMENDATION_POSITIVE => 'Positive',
            self::RECOMMENDATION_NEUTRAL => 'Neutre',
            self::RECOMMENDATION_NEGATIVE => 'Négative',
        ];
    }

    /**
     * Retourne la classe CSS pour le badge de recommandation
     */
    public static function getRecommendationBadgeClass(string $recommendation): string
    {
        return match($recommendation) {
            self::RECOMMENDATION_POSITIVE => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::RECOMMENDATION_NEUTRAL => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::RECOMMENDATION_NEGATIVE => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    public function candidate(): BelongsTo { return $this->belongsTo(Candidate::class); }
    public function evaluator(): BelongsTo { return $this->belongsTo(User::class, 'evaluator_id'); }
    public function interview(): BelongsTo { return $this->belongsTo(Interview::class); }
    public function drivingTest(): BelongsTo { return $this->belongsTo(DrivingTest::class); }
    public function responses(): HasMany { return $this->hasMany(EvaluationResponse::class); }

    public function calculateOverallRating(): float {
        $responses = $this->responses;
        if ($responses->isEmpty()) {
            return 0;
        }
        return round($responses->avg('rating'), 1);
    }

    public function getOverallRatingAttribute($value) {
        if ($value === null) {
            return $this->calculateOverallRating();
        }
        return $value;
    }
}