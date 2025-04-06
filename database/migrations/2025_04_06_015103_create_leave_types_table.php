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
        Schema::create('leave_types', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique(); // Nom du type (ex: Congé Payé Annuel)
    $table->text('description')->nullable();
    $table->boolean('requires_approval')->default(true); // Nécessite une approbation ?
    $table->boolean('affects_balance')->default(true); // Déduit du solde ? (false pour maladie sans solde peut-être)
    $table->boolean('is_active')->default(true); // Peut-on utiliser ce type ?
    $table->string('color_code')->nullable(); // Pour affichage calendrier (ex: #FF0000)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
