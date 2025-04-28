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
        $evaluatorFilter = $request->query('evaluator_id');
        $vehicleFilter = $request->query('vehicle_id');

        $query = DrivingTest::with(['candidate', 'evaluator', 'vehicle']); // Eager load

        // Filtre Statut
        if ($statusFilter && in_array($statusFilter, [
            DrivingTest::STATUS_PLANIFIE,
            DrivingTest::STATUS_REUSSI,
            DrivingTest::STATUS_ECHOUE,
            DrivingTest::STATUS_ANNULE
        ])) {
            $query->where('status', $statusFilter);
        }

        // Filtre Candidat (Pour Admin/Recruiter)
        $canFilterByCandidate = Auth::user()->isAdmin(); // || Auth::user()->isRecruiter();
        if ($canFilterByCandidate && $candidateFilter) {
            $query->where('candidate_id', $candidateFilter);
        }

        // Filtre Évaluateur
        if ($canFilterByCandidate && $evaluatorFilter) {
            $query->where('evaluator_id', $evaluatorFilter);
        }

        // Filtre Véhicule
        if ($canFilterByCandidate && $vehicleFilter) {
            $query->where('vehicle_id', $vehicleFilter);
        }

        // Filtre Date (sur test_date)
        if ($dateFromFilter) {
            try {
                $query->where('test_date', '>=', Carbon::parse($dateFromFilter)->startOfDay());
            } catch (\Exception $e) {
                $dateFromFilter = null;
            }
        }
        if ($dateToFilter) {
            try {
                $query->where('test_date', '<=', Carbon::parse($dateToFilter)->endOfDay());
            } catch (\Exception $e) {
                $dateToFilter = null;
            }
        }

        // Trier et Paginer
        $drivingTests = $query->orderBy('test_date', 'desc')->paginate(15);

        // Appends
        $drivingTests->appends($request->only(['status', 'candidate_id', 'date_from', 'date_to', 'evaluator_id', 'vehicle_id']));

        // Données pour les filtres de la vue
        $candidates = $canFilterByCandidate ? Candidate::orderBy('last_name')->get(['id', 'first_name', 'last_name']) : collect();
        $evaluators = $canFilterByCandidate ? User::orderBy('name')->get(['id', 'name']) : collect();
        $vehicles = $canFilterByCandidate ? Vehicle::orderBy('plate_number')->get(['id', 'plate_number', 'brand', 'model']) : collect();

        return view('driving-tests.index', compact(
            'drivingTests', 'candidates', 'evaluators', 'vehicles',
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
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'evaluator_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'test_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:' . implode(',', [
                DrivingTest::STATUS_PLANIFIE,
                DrivingTest::STATUS_REUSSI,
                DrivingTest::STATUS_ECHOUE,
                DrivingTest::STATUS_ANNULE
            ]),
        ], [
            'candidate_id.required' => 'Le candidat est obligatoire.',
            'evaluator_id.required' => 'L\'évaluateur est obligatoire.',
            'vehicle_id.required' => 'Le véhicule est obligatoire.',
            'test_date.required' => 'La date du test est obligatoire.',
            'test_date.after_or_equal' => 'La date du test doit être aujourd\'hui ou dans le futur.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
        ]);

        $data = $request->all();
        if (!isset($data['status'])) {
            $data['status'] = DrivingTest::STATUS_PLANIFIE;
        }

        $drivingTest = DrivingTest::create($data);

        // Met à jour le statut du candidat à 'test'
        $drivingTest->candidate->update(['status' => Candidate::STATUS_TEST]);

        return redirect()->route('driving-tests.index')
            ->with('success', 'Test de conduite créé avec succès.');
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
         $statuses = ['planifie', 'reussi', 'echoue', 'annule']; // Pour le select statut

        return view('driving-tests.edit', compact('drivingTest', 'candidates', 'evaluators', 'vehicles', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DrivingTest $drivingTest)
    {
        // Handle result-only mode
        if ($request->get('mode') === 'result') {
            $request->validate([
                'status' => ['required', Rule::in([DrivingTest::STATUS_REUSSI, DrivingTest::STATUS_ECHOUE])],
                'score' => 'nullable|integer|min:0|max:100',
                'passed' => ['nullable', 'boolean'],
                'results_summary' => 'nullable|string',
            ], [
                'status.required' => 'Le statut est obligatoire.',
                'status.in' => 'Le statut doit être Réussi ou Échoué.',
                'score.integer' => 'Le score doit être un nombre entier.',
                'score.min' => 'Le score doit être entre 0 et 100.',
                'score.max' => 'Le score doit être entre 0 et 100.',
            ]);
            $data = $request->only(['status', 'score', 'passed', 'results_summary']);
            $drivingTest->update($data);
            return redirect()->route('driving-tests.show', $drivingTest->id)
                ->with('success', 'Résultats du test enregistrés avec succès.');
        }

        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'evaluator_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'test_date' => 'required|date',
            'status' => 'required|in:' . implode(',', [
                DrivingTest::STATUS_PLANIFIE,
                DrivingTest::STATUS_REUSSI,
                DrivingTest::STATUS_ECHOUE,
                DrivingTest::STATUS_ANNULE
            ]),
            'score' => 'nullable|integer|min:0|max:100',
        ], [
            'candidate_id.required' => 'Le candidat est obligatoire.',
            'evaluator_id.required' => 'L\'évaluateur est obligatoire.',
            'vehicle_id.required' => 'Le véhicule est obligatoire.',
            'test_date.required' => 'La date du test est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            'score.integer' => 'Le score doit être un nombre entier.',
            'score.min' => 'Le score doit être compris entre 0 et 100.',
            'score.max' => 'Le score doit être compris entre 0 et 100.',
        ]);

        $data = $request->all();
        
        // Réinitialiser le score et passed si le statut n'est pas reussi ou echoue
        if (!in_array($data['status'], [DrivingTest::STATUS_REUSSI, DrivingTest::STATUS_ECHOUE])) {
            $data['score'] = null;
            $data['passed'] = null;
        }

        $drivingTest->update($data);

        return redirect()->route('driving-tests.index')
            ->with('success', 'Test de conduite mis à jour avec succès.');
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