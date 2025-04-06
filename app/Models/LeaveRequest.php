<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model {
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

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function leaveType(): BelongsTo { return $this->belongsTo(LeaveType::class); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approver_id'); }
}