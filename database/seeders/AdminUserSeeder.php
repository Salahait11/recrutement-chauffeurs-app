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
        // Vérifie si un utilisateur avec le rôle 'admin' existe déjà pour éviter conflits
        $existingAdmin = User::where('role', 'admin')->first();

        if (!$existingAdmin) {
            // Si aucun admin n'existe, crée l'admin par défaut
            User::updateOrCreate(
                [
                    // Champ unique pour trouver/créer l'utilisateur
                    'email' => 'admin@example.com' // CHOISIS UN EMAIL ADMIN PAR DEFAUT
                ],
                [
                    // Données à insérer ou mettre à jour
                    'name' => 'Admin Principal',
                    'password' => Hash::make('password'), // !! CHOISIS UN MOT DE PASSE SECURISE !!
                    'email_verified_at' => now(), // Marquer comme vérifié
                    'role' => 'admin' // Définir le rôle explicitement
                ]
            );
            $this->command->info('Default admin user created/updated.');
        } else {
             $this->command->info('Admin user already exists, skipping creation.');
        }

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