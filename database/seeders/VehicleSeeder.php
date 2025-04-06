<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle; // Assure-toi que le modèle est bien importé

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optionnel: Supprime les anciennes données pour éviter les doublons si tu relances
        Vehicle::query()->delete();

        Vehicle::insert([
            // Véhicule 1
            [
                'plate_number' => 'AA-123-BB',
                'brand' => 'Renault',
                'model' => 'Master',
                'type' => 'Fourgon',
                'year' => 2021,
                'is_available' => true,
                'notes' => null, // AJOUTÉ: Clé 'notes' avec valeur null
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Véhicule 2
            [
                'plate_number' => 'CC-456-DD',
                'brand' => 'Mercedes',
                'model' => 'Sprinter',
                'type' => 'Fourgon',
                'year' => 2022,
                'is_available' => true,
                'notes' => null, // AJOUTÉ: Clé 'notes' avec valeur null
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Véhicule 3 (celui qui avait déjà la clé 'notes')
            [
                'plate_number' => 'EE-789-FF',
                'brand' => 'Iveco',
                'model' => 'Daily',
                'type' => 'Camionnette',
                'year' => 2020,
                'is_available' => false,
                'notes' => 'En entretien', // Existant
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}