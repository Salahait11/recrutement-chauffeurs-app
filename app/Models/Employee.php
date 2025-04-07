<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'candidate_id',
        'employee_number',
        'hire_date',
        'job_title',
        'department',
        'manager_id',
        'work_location',
        'social_security_number',
        'bank_details',
        'status',
        'termination_date',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'termination_date' => 'date',
    ];

    // Relation vers l'enregistrement User associé
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    // Relation vers l'enregistrement Candidate d'origine
    public function candidate(): BelongsTo { return $this->belongsTo(Candidate::class); }

    // Relation vers le manager (qui est aussi un User)
    public function manager(): BelongsTo { return $this->belongsTo(User::class, 'manager_id'); }

   public function leaveRequests(): HasMany { return $this->hasMany(LeaveRequest::class); }
   public function absences(): HasMany { return $this->hasMany(Absence::class); }
}