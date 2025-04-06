<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('driving_tests', function (Blueprint $table) {
    $table->id();
    // Clé étrangère vers le candidat
    $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
    // Clé étrangère vers l'évaluateur (utilisateur)
    $table->foreignId('evaluator_id')->nullable()->constrained('users')->onDelete('set null');
    // Clé étrangère vers le véhicule utilisé
    $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
    // Date et heure du test
    $table->dateTime('test_date');
    // Itinéraire ou conditions spécifiques
    $table->text('route_details')->nullable();
    // Statut du test (similaire aux entretiens)
    $table->enum('status', ['scheduled', 'completed', 'canceled'])->default('scheduled');
    // Résultat global (Succès/Échec) - Peut-être redondant avec l'évaluation ? Mettons-le pour l'instant.
    $table->boolean('passed')->nullable(); // true = succès, false = échec, null = pas encore terminé/évalué
    // Commentaires / Résultats détaillés (lié à l'évaluation ?)
    $table->text('results_summary')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driving_tests');
    }
};
