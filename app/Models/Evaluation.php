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
        'driving_test_id', // Ajouté
        'conclusion',
        'recommendation',
        'overall_rating'
    ];

    public function candidate(): BelongsTo { return $this->belongsTo(Candidate::class); }
    public function evaluator(): BelongsTo { return $this->belongsTo(User::class, 'evaluator_id'); }
    public function interview(): BelongsTo { return $this->belongsTo(Interview::class); }
    // Ajoute la nouvelle relation
    public function drivingTest(): BelongsTo { return $this->belongsTo(DrivingTest::class); }

    public function responses(): HasMany { return $this->hasMany(EvaluationResponse::class); }
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}