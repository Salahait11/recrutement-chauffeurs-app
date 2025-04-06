<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function hasRole(string $roleName): bool
    {
        return $this->role === $roleName;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
    public function scheduledInterviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'scheduler_id');
    }
    public function conductedInterviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'interviewer_id');
    }
    

    public function isRecruiter(): bool // Ou 'rh_manager' etc.
    {
         // Un admin peut aussi être recruteur ? Ou juste le rôle spécifique ?
         // return $this->hasRole('recruiter') || $this->isAdmin();
         return $this->hasRole('recruiter');
    }

     public function isEmployee(): bool
    {
         return $this->hasRole('employee');
    }
 
public function evaluatedDrivingTests(): HasMany { return $this->hasMany(DrivingTest::class, 'evaluator_id'); }
public function createdOffers(): HasMany { return $this->hasMany(Offer::class, 'creator_id'); }
public function employee(): HasOne { return $this->hasOne(Employee::class); }
public function managedEmployees(): HasMany { return $this->hasMany(Employee::class, 'manager_id'); }
public function approvedLeaveRequests(): HasMany { return $this->hasMany(LeaveRequest::class, 'approver_id'); }
}
