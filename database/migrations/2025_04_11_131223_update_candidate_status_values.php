<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // D'abord, on supprime la contrainte enum existante
        DB::statement('ALTER TABLE candidates MODIFY COLUMN status VARCHAR(20)');
        
        // Mettre à jour les valeurs existantes
        DB::statement("UPDATE candidates SET status = CASE 
            WHEN status = 'new' THEN 'nouveau'
            WHEN status = 'contacted' THEN 'contacte'
            WHEN status = 'interview' THEN 'entretien'
            WHEN status = 'test' THEN 'test'
            WHEN status = 'offer' THEN 'offre'
            WHEN status = 'hired' THEN 'embauche'
            WHEN status = 'rejected' THEN 'refuse'
            ELSE status END");
            
        // Créer la nouvelle contrainte enum
        DB::statement("ALTER TABLE candidates MODIFY COLUMN status ENUM('nouveau', 'contacte', 'entretien', 'test', 'offre', 'embauche', 'refuse') DEFAULT 'nouveau'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // D'abord, on supprime la contrainte enum existante
        DB::statement('ALTER TABLE candidates MODIFY COLUMN status VARCHAR(20)');
        
        // Mettre à jour les valeurs existantes vers l'anglais
        DB::statement("UPDATE candidates SET status = CASE 
            WHEN status = 'nouveau' THEN 'new'
            WHEN status = 'contacte' THEN 'contacted'
            WHEN status = 'entretien' THEN 'interview'
            WHEN status = 'test' THEN 'test'
            WHEN status = 'offre' THEN 'offer'
            WHEN status = 'embauche' THEN 'hired'
            WHEN status = 'refuse' THEN 'rejected'
            ELSE status END");
            
        // Recréer l'ancienne contrainte enum
        DB::statement("ALTER TABLE candidates MODIFY COLUMN status ENUM('new', 'contacted', 'interview', 'test', 'offer', 'hired', 'rejected') DEFAULT 'new'");
    }
};
