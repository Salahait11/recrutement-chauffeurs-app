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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            // Clé étrangère vers le candidat
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            // Clé étrangère vers l'utilisateur qui a planifié (scheduler)
            $table->foreignId('scheduler_id')->nullable()->constrained('users')->onDelete('set null');
            // Clé étrangère vers l'intervieweur principal (on pourra étendre à plusieurs plus tard)
            $table->foreignId('interviewer_id')->nullable()->constrained('users')->onDelete('set null');
            // Date et heure de l'entretien
            $table->dateTime('interview_date');
            // Type d'entretien (ex: téléphonique, RH, technique, final)
            $table->string('type')->nullable();
            // Lieu (physique ou lien visio)
            $table->string('location')->nullable();
            // Statut de l'entretien
            $table->enum('status', ['scheduled', 'completed', 'canceled', 'rescheduled'])->default('scheduled');
            // Notes avant ou pendant l'entretien
            $table->text('notes')->nullable();
            // Compte-rendu après l'entretien (lié à l'évaluation ?) - Ajoutons le ici pour l'instant
            $table->text('feedback')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};