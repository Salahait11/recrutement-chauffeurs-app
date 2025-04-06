<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model {
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'requires_approval', 'affects_balance', 'is_active', 'color_code'
    ];

    // Relation vers les demandes de ce type
    public function leaveRequests(): HasMany { return $this->hasMany(LeaveRequest::class); }
}