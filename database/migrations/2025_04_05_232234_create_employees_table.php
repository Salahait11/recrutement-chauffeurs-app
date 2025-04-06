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
       Schema::create('employees', function (Blueprint $table) {
    $table->id(); // ID unique de l'employé

    // Clé étrangère vers l'enregistrement User (pour la connexion, permissions, etc.)
    // Un User peut être un employé, mais un employé DOIT être un User.
    $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

    // Clé étrangère vers l'enregistrement Candidate (pour l'historique du recrutement)
    // Une offre acceptée vient d'un candidat. Relation unique.
    $table->foreignId('candidate_id')->unique()->nullable()->constrained('candidates')->onDelete('set null');

    // Informations spécifiques à l'employé
    $table->string('employee_number')->unique()->nullable(); // Matricule employé (optionnel, peut être généré)
    $table->date('hire_date'); // Date d'embauche effective
    $table->string('job_title')->nullable(); // Intitulé de poste (ex: Chauffeur PL, Chauffeur Livreur)
    $table->string('department')->nullable(); // Service/Département
    // Clé étrangère vers le manager/responsable (un autre User)
    $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
    $table->string('work_location')->nullable(); // Lieu de travail principal

    // Informations administratives (peuvent être étendues)
    $table->string('social_security_number')->unique()->nullable(); // Numéro de sécurité sociale (sensible!)
    $table->text('bank_details')->nullable(); // Coordonnées bancaires (sensible!)
    $table->enum('status', ['active', 'on_leave', 'terminated'])->default('active'); // Statut actuel de l'employé
    $table->date('termination_date')->nullable(); // Date de fin de contrat

    // On pourrait ajouter des champs pour le contrat, salaire ici aussi,
    // mais ils sont déjà dans l'offre. On peut les copier si besoin.

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
