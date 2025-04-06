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
       Schema::create('offers', function (Blueprint $table) {
    $table->id();
    // Clé étrangère vers le candidat
    $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
    // Qui a créé l'offre (utilisateur)
    $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('set null');

    // Détails de l'offre
    $table->string('position_offered'); // Poste proposé
    $table->string('contract_type')->nullable(); // Ex: CDI, CDD, Intérim
    $table->date('start_date')->nullable(); // Date de début souhaitée/proposée
    $table->decimal('salary', 10, 2)->nullable(); // Rémunération (ex: 10 chiffres au total, 2 après la virgule)
    $table->string('salary_period')->nullable(); // Ex: Annuel, Mensuel, Horaire
    $table->text('benefits')->nullable(); // Avantages (texte libre ou JSON ?)
    $table->text('specific_conditions')->nullable(); // Conditions particulières

    // Statut de l'offre
    $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'expired', 'withdrawn'])->default('draft');
    // Date d'envoi de l'offre
    $table->timestamp('sent_at')->nullable();
    // Date de réponse du candidat
    $table->timestamp('responded_at')->nullable();
    // Date d'expiration de l'offre
    $table->date('expires_at')->nullable();

    // Contenu de l'offre (personnalisé)
    $table->longText('offer_text')->nullable(); // Stocker le texte complet de l'offre envoyée

    $table->timestamps(); // created_at, updated_at
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
