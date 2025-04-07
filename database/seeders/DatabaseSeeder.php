<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
    

        $this->call([
             AdminUserSeeder::class, // <<< ASSURE-TOI QUE CET APPEL EST PRESENT
            EvaluationCriteriaSeeder::class, // Garde les autres seeders utiles
            VehicleSeeder::class,
            LeaveTypeSeeder::class,
        ]);
    }
}