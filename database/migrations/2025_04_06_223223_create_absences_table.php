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
        Schema::create('absences', function (Blueprint $table) {
    $table->id();
    // Employé concerné
    $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
    // Date de l'absence (ou début si plusieurs jours)
    $table->date('absence_date');
    // Heure début/fin si nécessaire (pour retards/départs anticipés)
    $table->time('start_time')->nullable();
    $table->time('end_time')->nullable();
    // Type/Motif (on peut utiliser un enum ou une table séparée plus tard)
    $table->string('reason_type')->nullable()->comment('Ex: Maladie, Injustifiée, Retard, Départ Anticipé, Autre');
    // Description/Notes
    $table->text('notes')->nullable();
    // Justifiée ou non ?
    $table->boolean('is_justified')->default(false);
    // Qui a enregistré l'absence (utilisateur admin/RH)
    $table->foreignId('recorded_by_id')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps(); // created_at = date d'enregistrement
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
