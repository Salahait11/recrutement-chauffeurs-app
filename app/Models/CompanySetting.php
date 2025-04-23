<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'legal_name',
        'registration_number',
        'vat_number',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'website',
        'logo_path',
        'description',
        'working_hours',
        'holiday_calendar',
        'leave_policy',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'holiday_calendar' => 'array',
        'leave_policy' => 'array',
    ];

    public static function getSettings()
    {
        return static::first() ?? new static();
    }
} 