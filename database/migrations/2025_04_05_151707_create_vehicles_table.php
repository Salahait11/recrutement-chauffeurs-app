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
        Schema::create('vehicles', function (Blueprint $table) {
    $table->id();
    // Infos de base
    $table->string('plate_number')->unique(); // Plaque d'immatriculation (unique)
    $table->string('brand')->nullable();      // Marque (ex: Renault)
    $table->string('model')->nullable();      // Modèle (ex: Master)
    $table->string('type')->nullable();       // Type (ex: Fourgon, Camionnette, Poids Lourd)
    $table->year('year')->nullable();         // Année de mise en circulation
    // Statut/Disponibilité
    $table->boolean('is_available')->default(true); // Disponible pour les tests/missions ?
    $table->text('notes')->nullable();        // Notes diverses (entretien, particularités)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
