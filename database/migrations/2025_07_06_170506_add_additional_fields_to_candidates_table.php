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
        Schema::table('candidates', function (Blueprint $table) {
            // Numéro de candidat auto-généré (unique)
            $table->string('candidate_number')->unique()->nullable()->after('id');
            
            // CIN (Carte d'Identité Nationale)
            $table->string('cin')->unique()->nullable()->after('birth_date');
            
            // Situation familiale
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->after('cin');
            
            // Date d'obtention du permis de conduire
            $table->date('driving_license_obtained_date')->nullable()->after('driving_license_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'candidate_number',
                'cin',
                'marital_status',
                'driving_license_obtained_date'
            ]);
        });
    }
};
