<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Importer le modèle User
use Illuminate\Support\Facades\Hash; // Importer Hash

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'utilisateur admin par défaut
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin'
            ]
        );

        $this->command->info('Utilisateur administrateur créé/mis à jour.');

        // Optionnel: Créer un utilisateur 'employee' de base pour les tests ?
        /*
        User::updateOrCreate(
           ['email' => 'employee@example.com'],
           [
               'name' => 'Employé Test',
               'password' => Hash::make('password'),
               'email_verified_at' => now(),
               'role' => 'employee'
           ]
        );
        */
    }
}