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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            // Clé étrangère vers la table candidates
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            // Type de document (ex: cv, permit, letter, other) - utile pour filtrer/afficher
            $table->string('type')->nullable();
            // Chemin vers le fichier stocké (ex: 'candidate_documents/1/cv_jean_dupont.pdf')
            $table->string('file_path');
            // Nom original du fichier tel que téléchargé par l'utilisateur
            $table->string('original_name');
            // Type MIME du fichier (ex: application/pdf, image/jpeg)
            $table->string('mime_type')->nullable();
            // Taille du fichier en octets
            $table->unsignedBigInteger('size')->nullable();
            // Description optionnelle du document
            $table->text('description')->nullable();
            // Date d'expiration (utile pour permis, certifications)
            $table->date('expiry_date')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};