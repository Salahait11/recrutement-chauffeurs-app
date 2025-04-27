<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\File;

class DocumentController extends Controller
{
    /**
     * Store a newly created document in storage.
     * Lié à un candidat spécifique.
     */
    public function store(Request $request, Candidate $candidate)
    {
        // 1. Valider les données de la requête
        $validated = $request->validate([
            // Le champ 'document' doit être un fichier, requis, et on peut spécifier les types/taille
            'document' => [
                'required',
                File::types(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']) // Types de fichiers autorisés
                      ->max(5 * 1024), // Taille max 5MB (5 * 1024 KB)
            ],
            'document_type' => 'nullable|string|max:50', // Type optionnel mais recommandé
           // 'description' => 'nullable|string|max:255', // Si on ajoute la description
        ], [
            // Messages d'erreur personnalisés (optionnel)
            'document.required' => 'Veuillez sélectionner un fichier à télécharger.',
            'document.types' => 'Le type de fichier n\'est pas autorisé (PDF, DOC, DOCX, JPG, PNG).',
            'document.max' => 'Le fichier ne doit pas dépasser 5 Mo.',
        ]);

        // 2. Récupérer le fichier uploadé
        $file = $request->file('document'); // Récupère l'objet UploadedFile

        // 3. Stocker le fichier
        // On crée un dossier spécifique pour chaque candidat pour l'organisation
        // Le chemin sera 'public/candidate_documents/{candidate_id}/nom_fichier.ext'
        // La méthode store() génère un nom unique pour éviter les conflits
        // et retourne le chemin relatif (sans 'public/')
        $filePath = $file->store('candidate_documents/' . $candidate->id, 'public');

        // Si le stockage échoue (problème de permissions, etc.)
        if (!$filePath) {
            return Redirect::back()->with('document_error', 'Erreur lors du stockage du fichier.');
        }

        // 4. Enregistrer les informations du document en base de données
        try {
            $document = Document::create([
                'candidate_id' => $candidate->id,
                'type' => $validated['document_type'] ?? null, // Utilise le type validé s'il existe
               // 'description' => $validated['description'] ?? null,
                'file_path' => $filePath, // Chemin retourné par store()
                'original_name' => $file->getClientOriginalName(), // Nom original du fichier
                'mime_type' => $file->getClientMimeType(), // Type MIME détecté
                'size' => $file->getSize(), // Taille du fichier
                // 'expiry_date' => ... // A gérer si on ajoute ce champ au formulaire
            ]);
        } catch (\Exception $e) {
             // Si l'enregistrement DB échoue, on devrait supprimer le fichier stocké
             Storage::disk('public')->delete($filePath);
            \Log::error("Erreur sauvegarde document DB: " . $e->getMessage()); // Log l'erreur
             return Redirect::back()->with('document_error', 'Erreur lors de l\'enregistrement des informations du document.');
        }


        // 5. Rediriger vers la page du candidat avec un message de succès
        return Redirect::route('candidates.show', $candidate->id)
                       ->with('document_success', 'Document ajouté avec succès !');
    }

    /**
     * Met à jour le type du document.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'document_type' => 'nullable|string|max:50',
        ]);

        $document->type = $validated['document_type'] ?? null;
        $document->save();

        return Redirect::route('candidates.show', $document->candidate_id)
                       ->with('document_success', 'Type du document mis à jour !');
    }

    /**
     * Remove the specified document from storage.
     */
   public function destroy(Document $document)
    {
        // 1. Vérifier les autorisations (optionnel mais recommandé)
        // Par exemple, s'assurer que l'utilisateur connecté a le droit de supprimer
        // ce document ou les documents de ce candidat. Pour l'instant, on skip.
        // $this->authorize('delete', $document); // Nécessiterait une Policy

        // 2. Récupérer l'ID du candidat avant de supprimer le document (pour la redirection)
        $candidateId = $document->candidate_id;

        try {
            // 3. Supprimer le fichier physique du stockage
            // Utilise le disk 'public' car c'est là qu'on l'a stocké
            $deleted = Storage::disk('public')->delete($document->file_path);

            // Si la suppression du fichier échoue (fichier non trouvé, permissions...),
            // on peut choisir de continuer ou d'arrêter. Ici, on loggue et on continue
            // pour supprimer l'entrée DB quand même.
            if (!$deleted) {
                 \Log::warning("Fichier physique non trouvé ou non supprimé : " . $document->file_path);
                 // On pourrait retourner une erreur ici si on le souhaite :
                 // return Redirect::route('candidates.show', $candidateId)
                 //                ->with('document_error', 'Erreur : Fichier physique non trouvé.');
            }

            // 4. Supprimer l'enregistrement de la base de données
            $document->delete();

            // 5. Rediriger vers la page du candidat avec un message de succès
            return Redirect::route('candidates.show', $candidateId)
                           ->with('document_success', 'Document supprimé avec succès !');

        } catch (\Exception $e) {
            // En cas d'erreur lors de la suppression (DB ou autre)
             \Log::error("Erreur suppression document ID {$document->id}: " . $e->getMessage());
             return Redirect::route('candidates.show', $candidateId)
                            ->with('document_error', 'Erreur lors de la suppression du document.');
        }
    }
}