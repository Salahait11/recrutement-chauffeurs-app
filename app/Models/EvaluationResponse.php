<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationResponse extends Model {
    use HasFactory;
    protected $fillable = ['evaluation_id', 'criterion_id', 'rating', 'comment'];

    public function evaluation(): BelongsTo { return $this->belongsTo(Evaluation::class); }
    public function criterion(): BelongsTo { return $this->belongsTo(EvaluationCriterion::class, 'criterion_id'); }
}