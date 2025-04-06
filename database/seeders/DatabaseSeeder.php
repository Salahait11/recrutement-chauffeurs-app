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
        $now = Carbon::now(); // Date actuelle

        User::updateOrCreate(
    ['email' => 'test@example.com'],
    [
        'name' => 'Test User',
        'password' => Hash::make('password'),
        'created_at' => now()->format('Y-m-d H:i:s'), // Format explicite
        'updated_at' => now()->format('Y-m-d H:i:s')
    ]
);

        $this->call([
            EvaluationCriteriaSeeder::class,
            VehicleSeeder::class,
            LeaveTypeSeeder::class,
        ]);
    }
}