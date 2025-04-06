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
    Schema::create('candidates', function (Blueprint $table) {
        $table->id(); // Crée une colonne 'id' auto-incrémentée (clé primaire)
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique(); // Email unique
        $table->string('phone');
        $table->text('address')->nullable(); // Adresse, peut être nulle
        $table->date('birth_date')->nullable(); // Date de naissance, peut être nulle
        $table->string('driving_license_number')->unique()->nullable(); // Numéro de permis, unique, peut être nul
        $table->date('driving_license_expiry')->nullable(); // Date d'expiration du permis, peut être nulle
        // Statut du candidat (enum comme dans la doc simplifiée)
        $table->enum('status', ['new', 'contacted', 'interview', 'test', 'offer', 'hired', 'rejected'])->default('new');
        $table->text('notes')->nullable(); // Notes générales, peuvent être nulles
        $table->timestamps(); // Crée les colonnes 'created_at' et 'updated_at'
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
