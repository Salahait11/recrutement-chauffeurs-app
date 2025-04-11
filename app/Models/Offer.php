<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model {
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'salary',
        'start_date',
        'details',
        'status',
        'sent_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'sent_at' => 'datetime'
    ];

    // Définir les statuts possibles
    const STATUS_BROUILLON = 'brouillon';
    const STATUS_ENVOYEE = 'envoyee';
    const STATUS_ACCEPTEE = 'acceptee';
    const STATUS_REFUSEE = 'refusee';

    // Liste des statuts possibles
    public static $statuses = [
        self::STATUS_BROUILLON => 'Brouillon',
        self::STATUS_ENVOYEE => 'Envoyée',
        self::STATUS_ACCEPTEE => 'Acceptée',
        self::STATUS_REFUSEE => 'Refusée'
    ];

    public function candidate(): BelongsTo { return $this->belongsTo(Candidate::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    // Méthodes utilitaires pour vérifier le statut
    public function isBrouillon(): bool { return $this->status === self::STATUS_BROUILLON; }
    public function isEnvoyee(): bool { return $this->status === self::STATUS_ENVOYEE; }
    public function isAcceptee(): bool { return $this->status === self::STATUS_ACCEPTEE; }
    public function isRefusee(): bool { return $this->status === self::STATUS_REFUSEE; }
}