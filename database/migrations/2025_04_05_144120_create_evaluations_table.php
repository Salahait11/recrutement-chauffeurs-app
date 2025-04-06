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
        Schema::create('evaluations', function (Blueprint $table) {
    $table->id();
    // Clé étrangère vers le candidat évalué
    $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
    // Clé étrangère vers l'évaluateur (utilisateur)
    $table->foreignId('evaluator_id')->nullable()->constrained('users')->onDelete('set null');
    // Clé étrangère vers l'élément évalué (ici, l'entretien) - Polymorphique serait mieux, mais commençons simple
    $table->foreignId('interview_id')->nullable()->constrained('interviews')->onDelete('cascade');
    // On pourrait ajouter 'driving_test_id' plus tard
    // $table->foreignId('driving_test_id')->nullable()->constrained('driving_tests')->onDelete('cascade');

    // Conclusion / Recommandation générale
    $table->text('conclusion')->nullable();
    $table->enum('recommendation', ['positive', 'neutral', 'negative'])->nullable();
    // Note globale (optionnelle, pourrait être calculée)
    $table->unsignedTinyInteger('overall_rating')->nullable(); // Ex: 1 à 5

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
