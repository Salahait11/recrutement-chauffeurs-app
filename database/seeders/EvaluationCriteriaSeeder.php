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
            ['name' => 'Compétences Techniques', 'category' => 'Entretien Technique', 'description' => 'Connaissance des règles de conduite, mécanique de base...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Expérience Professionnelle', 'category' => 'Entretien RH', 'description' => 'Pertinence des expériences passées, durée...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Communication', 'category' => 'Entretien RH', 'description' => 'Clarté, écoute, expression orale...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Attitude et Comportement', 'category' => 'Général', 'description' => 'Motivation, professionnalisme, ponctualité...', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Respect Code de la Route (Test)', 'category' => 'Test Conduite', 'description' => 'Observation des règles pendant le test pratique', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}