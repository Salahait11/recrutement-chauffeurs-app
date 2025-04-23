<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model {
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'position_offered',
        'contract_type',
        'salary',
        'salary_period',
        'start_date',
        'benefits',
        'specific_conditions',
        'status',
        'sent_at',
        'responded_at',
        'expires_at',
        'offer_text',
        'creator_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
        'expires_at' => 'date',
        'salary' => 'decimal:2'
    ];

    // Définir les statuts possibles
    const STATUS_BROUILLON = 'brouillon';
    const STATUS_ENVOYEE = 'envoyee';
    const STATUS_ACCEPTEE = 'acceptee';
    const STATUS_REFUSEE = 'refusee';
    const STATUS_EXPIREE = 'expiree';
    const STATUS_RETIREE = 'retiree';

    // Liste des statuts possibles
    public static $statuses = [
        self::STATUS_BROUILLON => 'Brouillon',
        self::STATUS_ENVOYEE => 'Envoyée',
        self::STATUS_ACCEPTEE => 'Acceptée',
        self::STATUS_REFUSEE => 'Refusée',
        self::STATUS_EXPIREE => 'Expirée',
        self::STATUS_RETIREE => 'Retirée'
    ];

    // Relations
    public function candidate(): BelongsTo { 
        return $this->belongsTo(Candidate::class); 
    }

    public function createdBy(): BelongsTo { 
        return $this->belongsTo(User::class, 'creator_id'); 
    }

    // Méthodes utilitaires pour vérifier le statut
    public function isBrouillon(): bool { 
        return $this->status === self::STATUS_BROUILLON; 
    }

    public function isEnvoyee(): bool { 
        return $this->status === self::STATUS_ENVOYEE; 
    }

    public function isAcceptee(): bool { 
        return $this->status === self::STATUS_ACCEPTEE; 
    }

    public function isRefusee(): bool { 
        return $this->status === self::STATUS_REFUSEE; 
    }

    public function isExpiree(): bool { 
        return $this->status === self::STATUS_EXPIREE; 
    }

    public function isRetiree(): bool { 
        return $this->status === self::STATUS_RETIREE; 
    }

    // Méthode pour formater le salaire
    public function getFormattedSalaryAttribute(): string {
        if (!$this->salary) return '-';
        return number_format($this->salary, 2, ',', ' ') . ' €';
    }

    // Méthode pour vérifier si l'offre est expirée
    public function isExpired(): bool {
        if (!$this->expires_at) return false;
        return $this->expires_at->isPast();
    }

    // Méthode pour marquer l'offre comme expirée
    public function markAsExpired(): bool {
        if ($this->isExpired() && $this->status === self::STATUS_ENVOYEE) {
            $this->update(['status' => self::STATUS_EXPIREE]);
            return true;
        }
        return false;
    }
}