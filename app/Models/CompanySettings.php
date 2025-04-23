<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'logo_path',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'primary_color',
        'secondary_color',
    ];
}
