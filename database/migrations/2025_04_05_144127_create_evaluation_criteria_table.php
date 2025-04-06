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
        Schema::create('evaluation_criteria', function (Blueprint $table) {
    $table->id();
    // Nom du critère (ex: Compétences Techniques)
    $table->string('name')->unique();
    // Description optionnelle du critère
    $table->text('description')->nullable();
    // Catégorie (optionnel, pour regrouper)
    $table->string('category')->nullable();
    // Est-ce que ce critère est actif/utilisé ?
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_criteria');
    }
};
