<?php

namespace App\Http\Controllers;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
class CandidateController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
     $search = $request->query('search');
    $statusFilter = $request->query('status'); 
    Log::debug("Recherche Candidat - Terme: " . ($search ?? 'aucun')); // Log le terme

    $query = Candidate::query();

    if ($search) {
        Log::debug("Recherche Candidat - Application du filtre WHERE.");
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'LIKE', "%{$search}%")
              ->orWhere('last_name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
        });
    } else {
         Log::debug("Recherche Candidat - Pas de filtre appliqué.");
    }
        if ($statusFilter && $statusFilter !== 'all') { // Ignorer si vide ou 'all'
           
                   $query->where('status', $statusFilter);
            
        }

    $candidates = $query->orderBy('created_at', 'desc')->paginate(15);

        // Ajouter les DEUX filtres à la pagination
        $candidates->appends($request->only(['search', 'status']));

        // Définir les statuts possibles pour la liste déroulante
        $statuses = ['new', 'contacted', 'interview', 'test', 'offer', 'hired', 'rejected'];

        // Passer les candidats, la recherche ET le filtre statut à la vue
        return view('candidates.index', compact('candidates', 'search', 'statusFilter', 'statuses'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('candidates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // 1. Valider les données du formulaire
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:candidates,email', // Doit être unique dans la table candidates
        'phone' => 'required|string|max:20',
        'address' => 'nullable|string',
        'birth_date' => 'nullable|date',
        'driving_license_number' => 'nullable|string|max:255|unique:candidates,driving_license_number', // Unique aussi
        'driving_license_expiry' => 'nullable|date|after_or_equal:today', // La date d'expiration ne peut pas être passée
        'notes' => 'nullable|string',
    ]);

    // 2. Créer le candidat dans la base de données
    // Le statut par défaut ('new') est défini dans la migration, donc pas besoin de le spécifier ici
    $candidate = Candidate::create($validatedData);

    // 3. Rediriger vers la liste des candidats avec un message de succès
    return Redirect::route('candidates.index')->with('success', 'Candidat ajouté avec succès !');
    // Ou, si tu veux rediriger vers la page du candidat créé (on créera cette page plus tard) :
    // return Redirect::route('candidates.show', $candidate->id)->with('success', 'Candidat ajouté avec succès !');
}

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        return view('candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        return view('candidates.edit', compact('candidate'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Candidate $candidate) // Changement ici
{
    // 1. Valider les données du formulaire
    // Règles similaires à store(), mais attention à l'unicité de l'email/permis :
    // on doit ignorer l'enregistrement actuel lors de la vérification d'unicité.
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        // Pour 'unique', on spécifie la table, la colonne, et l'ID à ignorer
        'email' => 'required|email|unique:candidates,email,' . $candidate->id,
        'phone' => 'required|string|max:20',
        'address' => 'nullable|string',
        'birth_date' => 'nullable|date',
        'driving_license_number' => 'nullable|string|max:255|unique:candidates,driving_license_number,' . $candidate->id,
        'driving_license_expiry' => 'nullable|date|after_or_equal:today',
        'notes' => 'nullable|string',
        // On pourrait aussi ajouter la validation du statut ici si on permettait de le modifier via ce formulaire
    ]);

    // 2. Mettre à jour le candidat dans la base de données
    $candidate->update($validatedData);

    // 3. Rediriger vers la page de détails du candidat avec un message de succès
    return Redirect::route('candidates.show', $candidate->id)->with('success', 'Candidat mis à jour avec succès !');
    // Ou rediriger vers la liste:
    // return Redirect::route('candidates.index')->with('success', 'Candidat mis à jour avec succès !');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate) // Changement ici
{
    // TODO : Ajouter la logique de suppression des documents liés ?
    // Pour l'instant, on supprime juste le candidat.
    // On pourrait vouloir ajouter une confirmation ou des vérifications
    // (par exemple, ne pas supprimer un candidat déjà embauché ?)

    try {
        $candidate->delete(); // Supprime l'enregistrement de la base de données

        // Rediriger vers la liste des candidats avec un message de succès
        return Redirect::route('candidates.index')->with('success', 'Candidat supprimé avec succès !');

    } catch (\Exception $e) {
        // En cas d'erreur (par exemple, contrainte de clé étrangère si le candidat est lié ailleurs)
        // Loguer l'erreur peut être utile : \Log::error($e->getMessage());

        // Rediriger vers la page précédente (ou la liste) avec un message d'erreur
        return Redirect::back()->with('error', 'Erreur lors de la suppression du candidat.');
        // Ou vers la liste :
        // return Redirect::route('candidates.index')->with('error', 'Erreur lors de la suppression du candidat.');
    }
}
}
