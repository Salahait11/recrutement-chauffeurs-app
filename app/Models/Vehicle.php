<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Ajouter l'import
// use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model {
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'brand',
        'model',
        'type',
        'year',
        'is_available',
        'notes',
    ];

public function drivingTests(): HasMany { return $this->hasMany(DrivingTest::class); }
}