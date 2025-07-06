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
            AdminUserSeeder::class,      // Utilisateur admin
            LeaveTypeSeeder::class,      // Types de congés
            EventTypeSeeder::class,      // Types d'événements
        ]);
    }
}