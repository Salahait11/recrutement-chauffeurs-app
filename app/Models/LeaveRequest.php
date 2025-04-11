<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'duration_days',
        'reason',
        'status', 
        'approver_id', 
        'approved_at', 
        'approver_comment', 
        'attachment_path',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'approved_at' => 'datetime',
        'duration_days' => 'decimal:2',
    ];

    // Définir les statuts possibles
    const STATUS_EN_ATTENTE = 'en_attente';
    const STATUS_APPROUVE = 'approuve';
    const STATUS_REFUSE = 'refuse';
    const STATUS_ANNULE = 'annule';

    // Liste des statuts possibles
    public static $statuses = [
        self::STATUS_EN_ATTENTE => 'En attente',
        self::STATUS_APPROUVE => 'Approuvé',
        self::STATUS_REFUSE => 'Refusé',
        self::STATUS_ANNULE => 'Annulé'
    ];

    public function employee(): BelongsTo 
    { 
        return $this->belongsTo(Employee::class); 
    }

    public function leaveType(): BelongsTo 
    { 
        return $this->belongsTo(LeaveType::class); 
    }

    public function approver(): BelongsTo 
    { 
        return $this->belongsTo(User::class, 'approver_id'); 
    }

    // Méthodes utilitaires pour vérifier le statut
    public function isEnAttente(): bool
    {
        return $this->status === self::STATUS_EN_ATTENTE;
    }

    public function isApprouve(): bool
    {
        return $this->status === self::STATUS_APPROUVE;
    }

    public function isRefuse(): bool
    {
        return $this->status === self::STATUS_REFUSE;
    }

    public function isAnnule(): bool
    {
        return $this->status === self::STATUS_ANNULE;
    }

    // Calculer la durée du congé en jours
    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}