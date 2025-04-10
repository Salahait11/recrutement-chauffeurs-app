<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Employee; // Ajouté
use App\Models\User;
use Illuminate\Support\Facades\DB;    // Ajouté
use Illuminate\Support\Facades\Hash; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class OfferController extends Controller
{
    // Afficher la liste de toutes les offres
    public function index(Request $request)
    {
        // Récupérer les filtres
        $candidateFilter = $request->input('candidate_id');
        $statusFilter = $request->input('status', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        // Commencer la requête
        $query = Offer::with('candidate');
        
        // Appliquer les filtres
        if ($candidateFilter) {
            $query->where('candidate_id', $candidateFilter);
        }
        
        if ($statusFilter && $statusFilter != 'all') {
            $query->where('status', $statusFilter);
        }
        
        // Appliquer les filtres de date
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        // Récupérer les offres paginées
        $offers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        // Récupérer les candidats qui ont des offres pour le filtre
        $candidatesWithOffers = Candidate::whereHas('offers')->get();
        
        // Récupérer les statuts possibles pour le filtre
        $statuses = ['draft', 'sent', 'accepted', 'rejected', 'expired', 'withdrawn'];
        
        return view('offers.index', compact(
            'offers', 
            'candidatesWithOffers', 
            'statuses', 
            'candidateFilter', 
            'statusFilter',
            'dateFrom',
            'dateTo'
        ));
    }
    /**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    // 1. Valider les données
    $validatedData = $request->validate([
        'candidate_id' => 'required|exists:candidates,id',
        'position_offered' => 'required|string|max:255',
        'contract_type' => 'nullable|string|max:100',
        'start_date' => 'nullable|date',
        'salary' => 'nullable|numeric|min:0', // 'numeric' accepte les décimaux
        'salary_period' => 'nullable|string|max:50',
        'benefits' => 'nullable|string',
        'specific_conditions' => 'nullable|string',
        'expires_at' => 'nullable|date|after_or_equal:today',
       // 'offer_text' => 'nullable|string',
        // Récupérer le statut depuis le bouton cliqué
        'status' => 'required|in:draft,sent',
    ]);

    // 2. Ajouter l'ID du créateur
    $validatedData['creator_id'] = Auth::id();

    // 3. Définir la date d'envoi si le statut est 'sent'
    if ($validatedData['status'] === 'sent') {
        $validatedData['sent_at'] = now();
    }

    // 4. Créer l'offre
     try {
        $offer = Offer::create($validatedData);
     } catch (\Exception $e) {
         Log::error("Erreur création offre: " . $e->getMessage());
         return Redirect::back()->withInput()->with('error', 'Erreur lors de la création de l\'offre.');
     }


    // 5. Rediriger (vers la liste des offres ou les détails de l'offre créée)
    $message = $offer->status === 'sent' ? 'Offre enregistrée et marquée comme envoyée !' : 'Offre enregistrée comme brouillon.';
    // return Redirect::route('offers.show', $offer->id)->with('success', $message);
    return Redirect::route('offers.index')->with('success', $message);
}

    // Afficher les détails d'une offre spécifique
    public function show(Offer $offer)
    {
        $offer->load('candidate', 'creator');
        return view('offers.show', compact('offer')); // Vue à créer
    }

    // Afficher le formulaire pour modifier une offre
    public function edit(Offer $offer)
    {
         $offer->load('candidate');
         return view('offers.edit', compact('offer')); // Vue à créer
    }

   /**
 * Update the specified resource in storage.
 */
/**
 * Update the specified resource in storage.
 */
public function update(Request $request, Offer $offer)
{
    // Charger le candidat lié à l'offre
    $offer->load('candidate');
    $candidate = $offer->candidate;

    if (!$candidate) {
         return Redirect::route('offers.show', $offer->id)->with('error', 'Erreur : Candidat associé à l\'offre non trouvé.');
    }


    // Gérer les actions spécifiques de changement de statut
    if ($request->has('status_action')) {
        $action = $request->input('status_action');

        // --- LOGIQUE D'ACCEPTATION ---
        if ($action === 'accept' && $offer->status === 'sent') {

            // Utiliser une transaction pour toutes les opérations liées à l'embauche
            DB::beginTransaction();
            try {
                // 1. Mettre à jour l'offre
                $offer->status = 'accepted';
                $offer->responded_at = now();
                $offer->save();

                // 2. Mettre à jour le statut du candidat
                $candidate->status = 'hired';
                $candidate->save();

                // 3. Créer/Activer le compte Utilisateur pour l'employé
                // Est-ce que le candidat a déjà un compte User ?
                // Pour l'instant, supposons qu'on doit en créer un.
                // On pourrait avoir besoin d'un mot de passe temporaire ou généré.
                // !! ATTENTION : Gérer la sécurité et la communication du mot de passe !!
                $tempPassword = \Illuminate\Support\Str::random(10); // Génère un mot de passe aléatoire
                 $user = User::updateOrCreate(
    ['email' => $candidate->email],
    [
        'name' => $candidate->first_name . ' ' . $candidate->last_name,
        'password' => Hash::make($tempPassword),
        'email_verified_at' => now(), // Garder si ça fonctionne maintenant
        // 'role' => 'employee', // Optionnel, car c'est la valeur par défaut
    ]
);


                 // S'assurer que le rôle est bien défini si on utilise la gestion des rôles
                 // if ($user->wasRecentlyCreated || !$user->hasRole('employee')) {
                 //    $user->role = 'employee'; // Ou une méthode setRole() si elle existe
                 //    $user->save();
                 // }


                // 4. Créer l'enregistrement Employé
                $employee = Employee::create([
                    'user_id' => $user->id, // Lier à l'utilisateur créé/trouvé
                    'candidate_id' => $candidate->id, // Lier au candidat d'origine
                    // Copier les infos pertinentes de l'offre/candidat
                    'hire_date' => $offer->start_date ?? now()->toDateString(), // Date de l'offre ou aujourd'hui
                    'job_title' => $offer->position_offered,
                    // Ajouter d'autres champs par défaut ou via un formulaire intermédiaire ?
                    // 'employee_number' => 'EMP-' . str_pad($candidate->id, 5, '0', STR_PAD_LEFT), // Exemple de matricule
                     'status' => 'active', // Statut initial
                ]);

                // 5. Confirmer la transaction
                DB::commit();

                 // Envoyer email avec mot de passe temporaire ? Loguer le mot de passe ?
                 Log::info("Employé créé pour {$user->email}. MDP temporaire: {$tempPassword}"); // !! A SUPPRIMER EN PROD !!

                return Redirect::route('employees.show', $employee->id) // Rediriger vers la fiche employé (page à créer)
                               ->with('success', 'Offre ACCEPTÉE. Employé créé avec succès ! (MDP temporaire: '.$tempPassword.')'); // !! Message à adapter !!

            } catch (\Exception $e) {
                // Annuler la transaction en cas d'erreur
                DB::rollBack();
                Log::error("Erreur lors de l'acceptation de l'offre et création employé: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                return Redirect::route('offers.show', $offer->id)->with('error', 'Erreur lors de l\'acceptation de l\'offre.');
            }

        // --- FIN LOGIQUE D'ACCEPTATION ---

        } elseif ($action === 'reject' && $offer->status === 'sent') {
            // Logique pour le refus (inchangée)
            $offer->status = 'rejected';
            $offer->responded_at = now();
            $candidate->status = 'rejected'; // Mettre aussi à jour le statut candidat ?
            $candidate->save();
            $offer->save();
            return Redirect::route('offers.show', $offer->id)->with('success', 'Offre marquée comme REFUSÉE.');
        } else {
             return Redirect::route('offers.show', $offer->id)->with('error', 'Action de statut non valide ou offre non envoyée.');
        }
    }

    // Sinon, c'est une mise à jour standard depuis le formulaire edit (inchangé)
    $validatedData = $request->validate([ /* ... validations pour l'édition ... */ ]);
     try {
        $offer->update($validatedData);
     } catch (\Exception $e) { /* ... gestion erreur ... */ }
    return Redirect::route('offers.show', $offer->id)->with('success', 'Offre mise à jour avec succès !');
}

    // Supprimer une offre
   /**
 * Remove the specified resource from storage.
 */
public function destroy(Offer $offer)
{
     try {
         // Supprimer les éventuelles relations (si une offre acceptée créait un contrat par ex.)
         $offer->delete();
         return Redirect::route('offers.index')->with('success', 'Offre supprimée avec succès !');
     } catch (\Exception $e) {
         Log::error("Erreur suppression offre ID {$offer->id}: " . $e->getMessage());
         return Redirect::route('offers.index')->with('error', 'Erreur lors de la suppression de l\'offre.');
     }
}
}