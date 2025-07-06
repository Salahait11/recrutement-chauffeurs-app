<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $eventTypes = [
            [
                'name' => 'Formation',
                'description' => 'Session de formation professionnelle',
                'color' => '#4F46E5'
            ],
            [
                'name' => 'Réunion',
                'description' => 'Réunion d\'équipe ou de projet',
                'color' => '#10B981'
            ],
            [
                'name' => 'Maintenance',
                'description' => 'Maintenance du véhicule',
                'color' => '#F59E0B'
            ],
            [
                'name' => 'Évaluation',
                'description' => 'Évaluation des performances',
                'color' => '#EF4444'
            ]
        ];

        foreach ($eventTypes as $eventType) {
            EventType::updateOrCreate(
                ['name' => $eventType['name']],
                $eventType
            );
        }

        $this->command->info('Types d\'événements créés/mis à jour.');
    }
} 