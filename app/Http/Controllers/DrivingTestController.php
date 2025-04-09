<?php

namespace App\Http\Controllers;

use App\Models\DrivingTest;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Vehicle; // Ne pas oublier Vehicle
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class DrivingTestController extends Controller
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
        // Ajouter d'autres filtres si besoin (evaluator_id, vehicle_id)

        $query = DrivingTest::with(['candidate', 'evaluator', 'vehicle']);

        // Filtre Statut
        $allowedStatuses = ['scheduled', 'completed', 'canceled'];
        if ($statusFilter && $statusFilter !== 'all' && in_array($statusFilter, $allowedStatuses)) {
             $query->where('status', $statusFilter);
         } else { $statusFilter = null; }

        // Filtre Candidat (Admin)
        // !! Adapter rôles !!
         if (Auth::user()->isAdmin() && $candidateFilter) {
             $query->where('candidate_id', $candidateFilter);
         } else { $candidateFilter = null; }

         // Filtre Date (sur test_date)
         if ($dateFromFilter) {
             try { $query->where('test_date', '>=', Carbon::parse($dateFromFilter)->startOfDay()); }
             catch (\Exception $e) { $dateFromFilter = null; }
         }
          if ($dateToFilter) {
             try { $query->where('test_date', '<=', Carbon::parse($dateToFilter)->endOfDay()); }
             catch (\Exception $e) { $dateToFilter = null; }
         }

        // Trier et Paginer
        $drivingTests = $query->orderBy('test_date', 'desc')->paginate(15);

        // Appends (ajoute tous les filtres utilisés)
        $drivingTests->appends($request->only(['status', 'candidate_id', 'date_from', 'date_to']));

        // Données pour les filtres de la vue
        $statuses = $allowedStatuses;
        $candidates = Auth::user()->isAdmin() ? Candidate::orderBy('last_name')->get(['id', 'first_name', 'last_name']) : collect();

        // Passer les données et filtres actifs à la vue
        return view('driving_tests.index', compact( // Adapte le chemin si besoin
            'drivingTests', 'statuses', 'candidates',
            'statusFilter', 'candidateFilter', 'dateFromFilter', 'dateToFilter'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $candidates = Candidate::orderBy('last_name')->get(['id', 'first_name', 'last_name']);
        $evaluators = User::orderBy('name')->get(['id', 'name']); // Potentiels évaluateurs
        $vehicles = Vehicle::where('is_available', true) // Seulement les véhicules disponibles
                             ->orderBy('brand')->orderBy('model')
                             ->get(['id', 'plate_number', 'brand', 'model']);

        return view('driving_tests.create', compact('candidates', 'evaluators', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // 1. Valider les données
    $validatedData = $request->validate([
        'candidate_id' => 'required|exists:candidates,id',
        'evaluator_id' => 'required|exists:users,id',
        'vehicle_id' => 'nullable|exists:vehicles,id', // Optionnel, mais doit exister s'il est fourni
        'test_date' => 'required|date|after_or_equal:now',
        'route_details' => 'nullable|string',
    ], [
        // Messages d'erreur personnalisés...
        'candidate_id.required' => 'Veuillez sélectionner un candidat.',
        'evaluator_id.required' => 'Veuillez sélectionner un évaluateur.',
        'vehicle_id.exists' => 'Le véhicule sélectionné n\'est pas valide.',
        'test_date.required' => 'La date et l\'heure du test sont obligatoires.',
        'test_date.after_or_equal' => 'La date du test doit être dans le futur.',
    ]);

    // 2. Ajouter le statut par défaut
    $validatedData['status'] = 'scheduled';
    // On pourrait aussi ajouter l'ID de l'utilisateur qui planifie si c'est pertinent

    // 3. Créer le test dans la base de données
     try {
         DrivingTest::create($validatedData);
     } catch (\Exception $e) {
         \Log::error("Erreur création test conduite: " . $e->getMessage());
         return Redirect::back()
                        ->withInput()
                        ->with('error', 'Erreur lors de la planification du test.');
    }

    // 4. Rediriger vers la liste des tests avec un message de succès
    return Redirect::route('driving-tests.index')->with('success', 'Test de conduite planifié avec succès !');
}

    /**
     * Display the specified resource.
     */
   public function show(DrivingTest $drivingTest)
{
    $drivingTest->load(['candidate', 'evaluator', 'vehicle']);
    return view('driving_tests.show', compact('drivingTest'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DrivingTest $drivingTest) // Renommé le paramètre
    {
         $candidates = Candidate::orderBy('last_name')->get(['id', 'first_name', 'last_name']);
         $evaluators = User::orderBy('name')->get(['id', 'name']);
         $vehicles = Vehicle::orderBy('brand')->orderBy('model')->get(['id', 'plate_number', 'brand', 'model']); // Tous les véhicules pour l'édition ? Ou juste dispos ?
         $drivingTest->load(['candidate', 'evaluator', 'vehicle']);

        return view('driving_tests.edit', compact('drivingTest', 'candidates', 'evaluators', 'vehicles')); // Renommé les variables
    }

    /**
     * Update the specified resource in storage.
     */
    /**
 * Update the specified resource in storage.
 */
public function update(Request $request, DrivingTest $drivingTest)
{
    // 1. Valider les données
   // Dans la méthode update()
$validatedData = $request->validate([
    'candidate_id' => 'required|exists:candidates,id',
    'evaluator_id' => 'required|exists:users,id',
    'vehicle_id' => 'nullable|exists:vehicles,id',
    'test_date' => 'required|date',
    'route_details' => 'nullable|string',
    // AJOUTER/MODIFIER CES RÈGLES :
    'status' => 'required|in:scheduled,completed,canceled',
    // 'passed' est nullable, doit être 0 ou 1 si fourni
    'passed' => 'nullable|boolean', // Laravel convertira 0/1 en false/true
    'results_summary' => 'nullable|string',
]);

// Optionnel: Logique pour s'assurer que 'passed' n'est défini que si status = 'completed'
if ($validatedData['status'] !== 'completed') {
    $validatedData['passed'] = null; // Force passed à null si le test n'est pas terminé
}

    // 2. Mettre à jour le test
     try {
        $drivingTest->update($validatedData);
     } catch (\Exception $e) {
         \Log::error("Erreur MAJ test conduite ID {$drivingTest->id}: " . $e->getMessage());
         return Redirect::back()->withInput()->with('error', 'Erreur lors de la mise à jour du test.');
     }


    // 3. Rediriger
    return Redirect::route('driving-tests.show', $drivingTest->id)->with('success', 'Test de conduite mis à jour avec succès !');
}

    /**
     * Remove the specified resource from storage.
     */
    /**
 * Remove the specified resource from storage.
 */
public function destroy(DrivingTest $drivingTest)
{
     try {
         // Supprimer aussi les évaluations liées ? Ou juste annuler ? Pour l'instant on supprime.
         // Si on avait des évaluations liées spécifiquement aux tests de conduite :
         // $drivingTest->evaluations()->delete(); // Supprimerait les évaluations liées

         $drivingTest->delete();
         return Redirect::route('driving-tests.index')->with('success', 'Test de conduite supprimé avec succès !');
     } catch (\Exception $e) {
         \Log::error("Erreur suppression test conduite ID {$drivingTest->id}: " . $e->getMessage());
         return Redirect::route('driving-tests.index')->with('error', 'Erreur lors de la suppression du test.');
     }
}
}