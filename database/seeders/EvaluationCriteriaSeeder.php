<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\EvaluationCriterion; // Importer le modèle
use Illuminate\Support\Facades\DB; // Pour insérer plus rapidement si besoin

class EvaluationCriteriaSeeder extends Seeder {
    public function run(): void {
        // Supprimer les anciennes données (si on relance le seeder)
        EvaluationCriterion::query()->delete(); // Ou DB::table('evaluation_criteria')->delete();

        EvaluationCriterion::insert([
            // Critères pour les entretiens
            ['name' => 'Compétences Techniques', 'category' => 'Entretien Technique', 'description' => 'Connaissance des règles de conduite, mécanique de base...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Expérience Professionnelle', 'category' => 'Entretien RH', 'description' => 'Pertinence des expériences passées, durée...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Communication', 'category' => 'Entretien RH', 'description' => 'Clarté, écoute, expression orale...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Attitude et Comportement', 'category' => 'Général', 'description' => 'Motivation, professionnalisme, ponctualité...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Critères pour les tests de conduite
            ['name' => 'Respect du Code de la Route', 'category' => 'Test Conduite', 'description' => 'Application des règles de circulation, panneaux, feux...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maîtrise du Véhicule', 'category' => 'Test Conduite', 'description' => 'Contrôle de la direction, freinage, accélération...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sécurité et Vigilance', 'category' => 'Test Conduite', 'description' => 'Observation de l\'environnement, vérifications, distances de sécurité...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manoeuvres', 'category' => 'Test Conduite', 'description' => 'Créneau, marche arrière, demi-tour...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Adaptation au Trafic', 'category' => 'Test Conduite', 'description' => 'Gestion des situations de circulation, priorités...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Confort des Passagers', 'category' => 'Test Conduite', 'description' => 'Smoothness de la conduite, confort des passagers...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}