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
        Schema::create('leave_requests', function (Blueprint $table) {
    $table->id();
    // Employé faisant la demande
    $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
    // Type de congé demandé
    $table->foreignId('leave_type_id')->constrained('leave_types')->onDelete('cascade');
    // Dates
    $table->dateTime('start_date'); // Date et heure de début (important pour demi-journées)
    $table->dateTime('end_date');   // Date et heure de fin
    $table->decimal('duration_days', 5, 2)->nullable(); // Durée calculée en jours (ex: 2.5 jours) - Peut être calculé dynamiquement
    // Motif / Justification
    $table->text('reason')->nullable();
    // Statut de la demande
    $table->enum('status', ['pending', 'approved', 'rejected', 'canceled'])->default('pending');
    // Qui a traité la demande (manager/RH)
    $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null');
    // Date du traitement
    $table->timestamp('approved_at')->nullable();
    // Commentaire de l'approbateur
    $table->text('approver_comment')->nullable();
    // Document justificatif (chemin vers fichier) - Optionnel
    $table->string('attachment_path')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
