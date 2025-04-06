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
        Schema::table('evaluations', function (Blueprint $table) {
            // Ajouter la clé étrangère pour le test de conduite
            // La placer après 'interview_id' pour la logique
            $table->foreignId('driving_test_id')
                  ->nullable() // Une évaluation est soit pour un entretien, soit pour un test
                  ->after('interview_id') // Positionne la colonne dans la table
                  ->constrained('driving_tests') // Lie à la table driving_tests
                  ->onDelete('cascade'); // Si le test est supprimé, l'évaluation l'est aussi

            // Rendre interview_id nullable (si ce n'était pas déjà le cas)
            // Important: vérifier si elle était nullable dans la migration initiale
            // Si elle ne l'était pas, il faut la modifier :
            // $table->foreignId('interview_id')->nullable()->change(); // Modifie la colonne existante
            // Attention: 'change()' nécessite le package 'doctrine/dbal' : composer require doctrine/dbal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            // D'abord supprimer la contrainte et la colonne ajoutée
            $table->dropForeign(['driving_test_id']);
            $table->dropColumn('driving_test_id');

            // Remettre interview_id comme non nullable s'il l'était avant
            // $table->foreignId('interview_id')->nullable(false)->change();
        });
    }
};