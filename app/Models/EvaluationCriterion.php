<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationCriterion extends Model {
    use HasFactory;
    protected $fillable = ['name', 'description', 'category', 'is_active'];

    // Relation via les réponses
    public function responses(): HasMany { return $this->hasMany(EvaluationResponse::class, 'criterion_id'); }
}