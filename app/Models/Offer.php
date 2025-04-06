<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model {
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'creator_id',
        'position_offered',
        'contract_type',
        'start_date',
        'salary',
        'salary_period',
        'benefits',
        'specific_conditions',
        'status',
        'sent_at',
        'responded_at',
        'expires_at',
        'offer_text',
    ];

    protected $casts = [
        'start_date' => 'date',
        'salary' => 'decimal:2', // Important pour traiter comme décimal
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
        'expires_at' => 'date',
    ];

    public function candidate(): BelongsTo { return $this->belongsTo(Candidate::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'creator_id'); }
}