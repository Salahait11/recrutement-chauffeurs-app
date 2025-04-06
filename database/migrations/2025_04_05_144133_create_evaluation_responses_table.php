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
       Schema::create('evaluation_responses', function (Blueprint $table) {
    $table->id();
    // Clé étrangère vers l'évaluation globale
    $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
    // Clé étrangère vers le critère évalué
    $table->foreignId('criterion_id')->constrained('evaluation_criteria')->onDelete('cascade');
    // Note attribuée (ex: 1 à 5)
    $table->unsignedTinyInteger('rating');
    // Commentaire spécifique pour ce critère
    $table->text('comment')->nullable();
    // Assurer que la combinaison évaluation/critère est unique
    $table->unique(['evaluation_id', 'criterion_id']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_responses');
    }
};
