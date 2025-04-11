<?php
namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\DrivingTest;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DrivingTestController extends Controller
{
    public function index(Request $request)
    {
        $query = DrivingTest::with(['candidate', 'vehicle', 'interviewer']);
    
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
    
        if ($request->filled('date')) {
            $query->whereDate('test_date', '=', $request->input('date'));
        }

        $drivingTests = $query->latest()->paginate(10);

        return view('driving-tests.index', compact('drivingTests'));
    }

    public function create()
    {
        $candidates = Candidate::where('status', Candidate::STATUS_TEST)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    
        $vehicles = Vehicle::where('is_available', true)->orderBy('brand')->orderBy('model')->get();
    
        $admins = User::role('Admin')->orderBy('name')->get();
    
        if ($candidates->isEmpty()) {
            Log::warning('DrivingTestController@create: No candidates found with status \'test\'.');
            session()->flash('warning', 'Aucun candidat éligible (statut test) trouvé.');
        }
    
        if ($vehicles->isEmpty()) {
            Log::warning('DrivingTestController@create: No available vehicles found.');
            session()->flash('warning', 'Aucun véhicule disponible trouvé.');
        }
        if ($admins->isEmpty()) {
            Log::warning('DrivingTestController@create: No users found with the \'Admin\' role.');
            session()->flash('warning', 'Aucun examinateur (Admin) trouvé.');
        }

        return view('driving-tests.create', compact('candidates', 'vehicles','admins'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'interviewer_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'test_date' => 'required|date|after_or_equal:now',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            $candidate = Candidate::findOrFail($validatedData['candidate_id']);
            if($candidate->status !== Candidate::STATUS_TEST){
                throw new \Exception('Le candidat selectionné n est plus éligible pour un test de conduite.');
            }
            $interviewer = User::findOrFail($validatedData['interviewer_id']);
            if (!$interviewer->hasRole('Admin')) { // Adjust role if needed
                throw new \Exception('L\'utilisateur selectionné n\'est pas un examinateur valide.');
            }

            $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);
            if (!$vehicle->is_available) {
                throw new \Exception('Le véhicule sélectionné n\'est pas disponible.');
            }

            $existingTest = DrivingTest::where('candidate_id', $validatedData['candidate_id'])->where('status', DrivingTest::STATUS_SCHEDULED)->exists();
            if ($existingTest) {
                throw new \Exception('Le candidat a déjà un test de conduite planifié.');
            }

            $conflictingTest = DrivingTest::where('status', DrivingTest::STATUS_SCHEDULED)->where('test_date', $validatedData['test_date'])->where(function ($query) use ($validatedData) {
                    $query->where('vehicle_id', $validatedData['vehicle_id'])
                          ->orWhere('interviewer_id', $validatedData['interviewer_id']);
                })->exists();

            if ($conflictingTest) {
                throw new \Exception('Conflit d\'horaire: Le véhicule ou l\'examinateur est déjà réservé à cette date/heure.');
            }

            $test = DrivingTest::create([
                'candidate_id' => $validatedData['candidate_id'],
                'interviewer_id' => $validatedData['interviewer_id'],
                'vehicle_id' => $validatedData['vehicle_id'],
                'test_date' => $validatedData['test_date'],
                'notes' => $validatedData['notes'],
                'status' => DrivingTest::STATUS_SCHEDULED
            ]);

            DB::commit();

            return redirect()->route('driving-tests.show', $test)->with('success', 'Test de conduite planifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création test de conduite: ' . $e->getMessage());
            $errorMessage = 'Erreur lors de la planification du test de conduite.';
            $knownMessages = [
                'Le candidat selectionné n est plus éligible pour un test de conduite.',
                'L\'utilisateur selectionné n\'est pas un examinateur valide.',
                'Le véhicule sélectionné n\'est pas disponible.',
                'Conflit d\'horaire: Le véhicule ou l\'examinateur est déjà réservé à cette date/heure.',
                'Le candidat a déjà un test de conduite planifié.'
            ];
            if (in_array($e->getMessage(), $knownMessages)) {
                $errorMessage = $e->getMessage();
            }

            return back()->withInput()->with('error', $errorMessage);
        }
    }

    public function show(DrivingTest $drivingTest)
    {
        $drivingTest->load(['candidate', 'vehicle', 'interviewer']);
        return view('driving-tests.show', compact('drivingTest'));
    }

    public function edit(DrivingTest $drivingTest)
    {
        if ($drivingTest->status !== DrivingTest::STATUS_SCHEDULED) {
            return redirect()->route('driving-tests.show', $drivingTest)->with('error', 'Seuls les tests planifiés peuvent être modifiés.');
        }
        $candidates = Candidate::where('status', Candidate::STATUS_TEST)->orderBy('last_name')->orderBy('first_name')->get();
        $vehicles = Vehicle::where('is_available', true)->orderBy('brand')->orderBy('model')->get();
        $admins = User::role('Admin')->orderBy('name')->get();

        return view('driving-tests.edit', compact('drivingTest','candidates', 'vehicles', 'admins'));
    }

    public function update(Request $request, DrivingTest $drivingTest)
    {
        if ($drivingTest->status !== DrivingTest::STATUS_SCHEDULED) {
            return redirect()->route('driving-tests.show', $drivingTest)->with('error', 'Seuls les tests planifiés peuvent être modifiés.');
        }

        $validatedData = $request->validate([
            'interviewer_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'test_date' => 'required|date|after_or_equal:now',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            $interviewer = User::findOrFail($validatedData['interviewer_id']);
            if (!$interviewer->hasRole('Admin')) {
                throw new \Exception('L\'examinateur selectionné n\'est plus valide.');
            }
            $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);
            if (!$vehicle->is_available) {
                throw new \Exception('Le véhicule selectionné n\'est plus disponible.');
            }
            $conflictingTest = DrivingTest::where('status', DrivingTest::STATUS_SCHEDULED)->where('id', '!=', $drivingTest->id)->where('test_date', $validatedData['test_date'])->where(function ($query) use ($validatedData) {
                $query->where('vehicle_id', $validatedData['vehicle_id'])
                    ->orWhere('interviewer_id', $validatedData['interviewer_id']);
                })
                ->exists(); // Fix: Removed an extra ->exists();
            if ($conflictingTest) {
                throw new \Exception('Conflit d\'horaire: Le véhicule ou l\'examinateur est déjà reservé à cette date/heure pour un autre test.');
            }

            $drivingTest->update($validatedData);
            DB::commit();

            return redirect()->route('driving-tests.show', $drivingTest)->with('success', 'Test de conduite mis à jour avec succès.');
        } catch (\Exception $e) {
             DB::rollBack();
             Log::error("Erreur mise à jour test de conduite (ID: {$drivingTest->id}): " . $e->getMessage());

             $errorMessage = 'Erreur lors de la mise à jour du test de conduite.';
              $knownMessages = [
                'L\'examinateur selectionné n\'est plus valide.',
                'Le véhicule selectionné n\'est plus disponible.',
                'Conflit d\'horaire: Le véhicule ou l\'examinateur est déjà reservé à cette date/heure pour un autre test.'
              ];
             if (in_array($e->getMessage(), $knownMessages)) {
                $errorMessage = $e->getMessage();
             }

            return back()->withInput()->with('error', $errorMessage);
        }
    }
    
   
    
    public function updateStatus(Request $request, DrivingTest $drivingTest)
    {
        $validatedData = $request->validate([
            'status' => ['required', 'in:' . implode(',', [
                DrivingTest::STATUS_PASSED,
                DrivingTest::STATUS_FAILED,
                DrivingTest::STATUS_CANCELED,
            ])],
            'feedback' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            if ($drivingTest->status !== DrivingTest::STATUS_SCHEDULED) {
                throw new \Exception('Seuls les tests planifiés peuvent être complétés ou annulés.');
            }

            $drivingTest->update([
                'status' => $validatedData['status'],
                'feedback' => $validatedData['feedback']
            ]);

            if ($validatedData['status'] === DrivingTest::STATUS_FAILED) {
                $drivingTest->candidate->update(['status' => Candidate::STATUS_REFUSE]);
            } elseif ($validatedData['status'] === DrivingTest::STATUS_PASSED) {
                // $drivingTest->candidate->update(['status' => Candidate::STATUS_OFFER]);
            }

            DB::commit();

            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('success', 'Statut du test de conduite mis à jour avec succès.');


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour statut test de conduite (ID: {$drivingTest->id}): " . $e->getMessage());

            $errorMessage = 'Erreur lors de la mise à jour du statut du test.';
             if ($e->getMessage() === 'Seuls les tests planifiés peuvent être complétés ou annulés.') {
                $errorMessage = $e->getMessage();
             }

            return back()->with('error', $errorMessage);
        }
    }



    public function destroy(DrivingTest $drivingTest)
    {
        if(!in_array($drivingTest->status, [DrivingTest::STATUS_SCHEDULED, DrivingTest::STATUS_CANCELED])){
            return back()->with('error', 'Seuls les tests planifiés ou annulés peuvent être supprimés.');
        }
        try {
            $drivingTest->delete();
            return redirect()->route('driving-tests.index')
                ->with('success', 'Test de conduite supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur suppression test de conduite (ID: '.$drivingTest->id.'): '.$e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression du test de conduite.');
        }
    }
}
