<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Candidate; // Pour lister les candidats dans le formulaire
use App\Models\User;    // Pour lister les interviewers potentiels
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pour récupérer l'utilisateur connecté (planificateur)
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class InterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request) // <<< AJOUTER Request
    {
        // Récupérer les filtres
        $statusFilter = $request->query('status');
        $candidateFilter = $request->query('candidate_id');
        $dateFromFilter = $request->query('date_from');
        $dateToFilter = $request->query('date_to');

        // Requête de base
        $query = Interview::with(['candidate', 'interviewer']); // Eager load

        // Appliquer filtre Statut
        $allowedStatuses = ['scheduled', 'completed', 'canceled', 'rescheduled'];
        if ($statusFilter && $statusFilter !== 'all' && in_array($statusFilter, $allowedStatuses)) {
             $query->where('status', $statusFilter);
         } else { $statusFilter = null; }

        // Appliquer filtre Candidat (accessible à l'admin)
         // !! Ajouter logique de rôle si nécessaire !!
         if (Auth::user()->isAdmin() && $candidateFilter) {
             $query->where('candidate_id', $candidateFilter);
         } else { $candidateFilter = null; } // Ignorer si pas admin ou pas de filtre

         // Appliquer filtre Date (sur interview_date)
         if ($dateFromFilter) {
             try { $query->where('interview_date', '>=', Carbon::parse($dateFromFilter)->startOfDay()); }
             catch (\Exception $e) { $dateFromFilter = null; }
         }
          if ($dateToFilter) {
             try { $query->where('interview_date', '<=', Carbon::parse($dateToFilter)->endOfDay()); }
             catch (\Exception $e) { $dateToFilter = null; }
         }

        // Trier et Paginer
        $interviews = $query->orderBy('interview_date', 'desc')->paginate(15);

        // Ajouter filtres à la pagination
        $interviews->appends($request->only(['status', 'candidate_id', 'date_from', 'date_to']));

        // Données pour les filtres de la vue
        $statuses = $allowedStatuses;
        $candidates = Auth::user()->isAdmin() ? Candidate::orderBy('last_name')->get(['id', 'first_name', 'last_name']) : collect();

        // Passer les données à la vue
        return view('interviews.index', compact(
            'interviews', 'statuses', 'candidates',
            'statusFilter', 'candidateFilter', 'dateFromFilter', 'dateToFilter'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $candidates = Candidate::orderBy('last_name')->get(['id', 'first_name', 'last_name']);

        // Récupérer les utilisateurs (potentiels interviewers) pour la liste déroulante
        // On pourrait filtrer par rôle plus tard
        $interviewers = User::orderBy('name')->get(['id', 'name']);

        return view('interviews.create', compact('candidates', 'interviewers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Valider les données
    $validatedData = $request->validate([
        'candidate_id' => 'required|exists:candidates,id', // Doit exister dans la table candidates
        'interviewer_id' => 'required|exists:users,id',     // Doit exister dans la table users
        'interview_date' => 'required|date|after_or_equal:now', // Doit être une date valide et future
        'type' => 'nullable|string|max:255',
        'location' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ], [
        'candidate_id.required' => 'Veuillez sélectionner un candidat.',
        'candidate_id.exists' => 'Le candidat sélectionné n\'est pas valide.',
        'interviewer_id.required' => 'Veuillez sélectionner un intervieweur.',
        'interviewer_id.exists' => 'L\'intervieweur sélectionné n\'est pas valide.',
        'interview_date.required' => 'La date et l\'heure sont obligatoires.',
        'interview_date.date' => 'La date et l\'heure ne sont pas valides.',
        'interview_date.after_or_equal' => 'La date de l\'entretien doit être dans le futur.',
    ]);

    // 2. Ajouter l'ID de l'utilisateur connecté comme planificateur (scheduler)
    $validatedData['scheduler_id'] = Auth::id(); // Récupère l'ID de l'utilisateur authentifié

    // 3. Ajouter le statut par défaut (sera 'scheduled' grâce à la migration, mais on peut le forcer)
    $validatedData['status'] = 'scheduled';

    // 4. Créer l'entretien dans la base de données
    try {
         Interview::create($validatedData);
    } catch (\Exception $e) {
        \Log::error("Erreur création entretien: " . $e->getMessage());
        return Redirect::back()
                        ->withInput() // Renvoie les anciennes entrées au formulaire
                        ->with('error', 'Erreur lors de la planification de l\'entretien.');
    }


    // 5. Rediriger vers la liste des entretiens avec un message de succès
    return Redirect::route('interviews.index')->with('success', 'Entretien planifié avec succès !');
    }

    /**
     * Display the specified resource.
     */
   public function show(Interview $interview)
    {
        // Chargement des relations si nécessaire (déjà fait par Route Model Binding ?)
        $interview->load(['candidate', 'scheduler', 'interviewer']);
        return view('interviews.show', compact('interview'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interview $interview)
    {
        $candidates = Candidate::orderBy('last_name')->get(['id', 'first_name', 'last_name']);
        $interviewers = User::orderBy('name')->get(['id', 'name']);
        $interview->load('candidate', 'interviewer'); // Charger les relations pour le formulaire

        return view('interviews.edit', compact('interview', 'candidates', 'interviewers'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Interview $interview)
    {
        // 1. Valider les données (similaire à store, mais date peut être maintenant)
    $validatedData = $request->validate([
        'candidate_id' => 'required|exists:candidates,id',
        'interviewer_id' => 'required|exists:users,id',
        'interview_date' => 'required|date', // Pas besoin de 'after_or_equal:now' ici
        'type' => 'nullable|string|max:255',
        'location' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'status' => 'sometimes|required|in:scheduled,completed,canceled,rescheduled', // Si on ajoute le champ statut
        'feedback' => 'nullable|string', // Si on ajoute le feedback
    ]);

    // On pourrait vouloir mettre à jour le 'scheduler_id' si c'est un autre user qui modifie,
    // mais laissons le scheduler original pour l'instant.

    // 2. Mettre à jour l'entretien
    try {
         $interview->update($validatedData);
    } catch (\Exception $e) {
         \Log::error("Erreur MAJ entretien ID {$interview->id}: " . $e->getMessage());
         return Redirect::back()
                        ->withInput()
                        ->with('error', 'Erreur lors de la mise à jour de l\'entretien.');
    }

    // 3. Rediriger (par exemple, vers les détails de l'entretien ou la liste)
    return Redirect::route('interviews.show', $interview->id)->with('success', 'Entretien mis à jour avec succès !');
    // Ou: return Redirect::route('interviews.index')->with('success', 'Entretien mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
{
    // On pourrait changer le statut à 'canceled' au lieu de supprimer,
    // mais pour suivre le CRUD standard, supprimons pour l'instant.
     try {
         $interview->delete();
         return Redirect::route('interviews.index')->with('success', 'Entretien supprimé avec succès !');
     } catch (\Exception $e) {
         \Log::error("Erreur suppression entretien ID {$interview->id}: " . $e->getMessage());
         return Redirect::route('interviews.index')->with('error', 'Erreur lors de la suppression de l\'entretien.');
     }
}
}
