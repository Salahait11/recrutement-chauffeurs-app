<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model {
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'absence_date',
        'start_time',
        'end_time',
        'reason_type',
        'notes',
        'is_justified',
        'recorded_by_id',
    ];

    protected $casts = [
        'absence_date' => 'date',
        'is_justified' => 'boolean',
        // 'start_time' => 'datetime:H:i', // Si on veut caster l'heure
        // 'end_time' => 'datetime:H:i',
    ];

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function recorder(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by_id'); }
}