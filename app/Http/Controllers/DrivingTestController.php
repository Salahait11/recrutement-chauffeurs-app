<?php

namespace App\Http\Controllers;

// Models
use App\Models\DrivingTest;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Evaluation; // Si on veut vérifier l'évaluation existante

// Facades & Classes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class DrivingTestController extends Controller
{
    
    public function index(Request $request)
    {
        // Récupérer les filtres
        $statusFilter = $request->query('status');
        $candidateFilter = $request->query('candidate_id');
        $dateFromFilter = $request->query('date_from');
        $dateToFilter = $request->query('date_to');
        $evaluatorFilter = $request->query('evaluator_id'); // Filtre évaluateur optionnel
        $vehicleFilter = $request->query('vehicle_id'); // Filtre véhicule optionnel

        $query = DrivingTest::with(['candidate', 'evaluator', 'vehicle']); // Eager load

        // Filtre Statut
        $allowedStatuses = ['scheduled', 'completed', 'canceled'];
        if ($statusFilter && $statusFilter !== 'all' && in_array($statusFilter, $allowedStatuses)) {
             $query->where('status', $statusFilter);
         } else { $statusFilter = null; }

        // Filtre Candidat (Pour Admin/Recruiter)
         // !! Adapter logique de rôle !!
         $canFilterByCandidate = Auth::user()->isAdmin(); // || Auth::user()->isRecruiter();
         if ($canFilterByCandidate && $candidateFilter) {
             $query->where('candidate_id', $candidateFilter);
         } else { $candidateFilter = null; }

        // Filtre Evaluateur (Pour Admin/Recruiter)
         if ($canFilterByCandidate && $evaluatorFilter) {
             $query->where('evaluator_id', $evaluatorFilter);
         } else { $evaluatorFilter = null; }

         // Filtre Vehicule (Pour Admin/Recruiter)
         if ($canFilterByCandidate && $vehicleFilter) {
             $query->where('vehicle_id', $vehicleFilter);
         } else { $vehicleFilter = null; }

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

        // Appends
        $drivingTests->appends($request->only(['status', 'candidate_id', 'date_from', 'date_to', 'evaluator_id', 'vehicle_id']));

        // Données pour les filtres de la vue
        $statuses = $allowedStatuses;
        $candidates = $canFilterByCandidate ? Candidate::orderBy('last_name')->get(['id', 'first_name', 'last_name']) : collect();
        $evaluators = $canFilterByCandidate ? User::orderBy('name')->get(['id', 'name']) : collect(); // Ou filtrer par rôle évaluateur?
        $vehicles = $canFilterByCandidate ? Vehicle::orderBy('plate_number')->get(['id', 'plate_number', 'brand', 'model']) : collect();


        return view('driving-tests.index', compact(
            'drivingTests', 'statuses', 'candidates', 'evaluators', 'vehicles',
            'statusFilter', 'candidateFilter', 'dateFromFilter', 'dateToFilter', 'evaluatorFilter', 'vehicleFilter'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // !! Vérifier autorisation de créer !!
        // $this->authorize('create', DrivingTest::class);

        $candidates = Candidate::whereNotIn('status', ['hired', 'rejected'])->orderBy('last_name')->get(['id', 'first_name', 'last_name']); // Candidats pertinents
        $evaluators = User::orderBy('name')->get(['id', 'name']); // A affiner avec rôles
        $vehicles = Vehicle::where('is_available', true)->orderBy('plate_number')->get(['id', 'plate_number', 'brand', 'model']);

        return view('driving-tests.create', compact('candidates', 'evaluators', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // !! Vérifier autorisation !!
        // $this->authorize('create', DrivingTest::class);

        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'evaluator_id' => 'required|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'test_date' => 'required|date|after_or_equal:today',
            'route_details' => 'nullable|string|max:1000',
        ],[/* ... messages custom ... */]);

        $validatedData['status'] = 'scheduled'; // Statut initial

        try {
            DrivingTest::create($validatedData);
            return Redirect::route('driving-tests.index')->with('success', 'Test de conduite planifié.');
        } catch (\Exception $e) {
            Log::error("Erreur création test conduite: " . $e->getMessage());
            return Redirect::back()->withInput()->with('error', 'Erreur planification test.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DrivingTest $drivingTest) // Utilise le nom de variable cohérent
    {
        // !! Vérifier autorisation !!
        // $this->authorize('view', $drivingTest);

        $drivingTest->load(['candidate', 'evaluator', 'vehicle', 'evaluations']); // Charger relations + évaluations
        return view('driving-tests.show', compact('drivingTest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DrivingTest $drivingTest)
    {
         // !! Vérifier autorisation !!
         // $this->authorize('update', $drivingTest);

         $drivingTest->load(['candidate', 'evaluator', 'vehicle']);
         $candidates = Candidate::orderBy('last_name')->get(['id', 'first_name', 'last_name']);
         $evaluators = User::orderBy('name')->get(['id', 'name']);
         $vehicles = Vehicle::orderBy('plate_number')->get(['id', 'plate_number', 'brand', 'model']);
         $statuses = [DrivingTest::STATUS_SCHEDULED, DrivingTest::STATUS_PASSED, DrivingTest::STATUS_FAILED, DrivingTest::STATUS_CANCELED]; // Pour le select statut

        return view('driving-tests.edit', compact('drivingTest', 'candidates', 'evaluators', 'vehicles', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DrivingTest $drivingTest)
    {
         // !! Vérifier autorisation !!
         // $this->authorize('update', $drivingTest);

         $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'evaluator_id' => 'required|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'test_date' => 'required|date',
            'route_details' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in([DrivingTest::STATUS_SCHEDULED, DrivingTest::STATUS_PASSED, DrivingTest::STATUS_FAILED, DrivingTest::STATUS_CANCELED])],
            'passed' => 'nullable|boolean', // 0 ou 1 du formulaire
            'results_summary' => 'nullable|string|max:2000',
        ]);

        if ($validatedData['status'] !== DrivingTest::STATUS_PASSED && $validatedData['status'] !== DrivingTest::STATUS_FAILED) {
            $validatedData['passed'] = null;
        }
         try {
            $drivingTest->update($validatedData);
            return Redirect::route('driving-tests.show', $drivingTest->id)->with('success', 'Test mis à jour.');
        } catch (\Exception $e) {
            Log::error("Erreur MAJ test conduite ID {$drivingTest->id}: " . $e->getMessage());
            return Redirect::back()->withInput()->with('error', 'Erreur mise à jour test.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DrivingTest $drivingTest)
    {
        // !! Vérifier autorisation !!
        // $this->authorize('delete', $drivingTest);

        try {
            // Supprimer les évaluations liées avant ? Ou laisser cascade ?
            // $drivingTest->evaluations()->delete();
            $drivingTest->delete();
            return Redirect::route('driving-tests.index')->with('success', 'Test supprimé.');
        } catch (QueryException $e) {
             Log::error("Erreur suppression test conduite FK ID {$drivingTest->id}: ".$e->getMessage());
             return Redirect::route('driving-tests.index')->with('error', 'Erreur suppression: contrainte BDD.');
        } catch (\Exception $e) {
            Log::error("Erreur suppression test conduite ID {$drivingTest->id}: ".$e->getMessage());
            return Redirect::route('driving-tests.index')->with('error', 'Erreur suppression.');
        }
    }
}