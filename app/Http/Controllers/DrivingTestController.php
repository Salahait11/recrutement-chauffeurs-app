<?php

namespace App\Http\Controllers;

use App\Models\DrivingTest;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\User; // Import User model
use App\Models\Vehicle; // Import Vehicle model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role; // Assuming spatie/laravel-permission

class DrivingTestController extends Controller
{
    public function index(Request $request)
    {
        $query = DrivingTest::with(['candidate', 'vehicle', 'interviewer']); // Eager load relationships

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('date')) {
            $query->whereDate('test_date', $request->input('date'));
        }

        $drivingTests = $query->latest()->paginate(10);

        return view('driving-tests.index', compact('drivingTests'));
    }

    public function create()
    {
        // Fetch candidates who are eligible for a test
        $candidates = Candidate::whereIn('status', [Candidate::STATUS_TEST]) // Keep eligibility criteria, adjust if needed
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Fetch available vehicles
        $vehicles = Vehicle::where('status', 'available') // Fetch only available vehicles
                            ->orderBy('make')
                            ->orderBy('model')
                            ->get();

        // Fetch users with the 'Admin' role (adjust 'Admin' if your role name is different)
        $admins = User::whereHas('roles', function ($query) {
                        $query->where('name', 'Admin');
                    })->orderBy('name')->get();


        // Check if data is empty and potentially add feedback for the user
        if ($candidates->isEmpty()) {
            // Optional: Add a flash message or log if no eligible candidates are found
            Log::warning("DrivingTestController@create: No candidates found with status 'test'.");
        }
         if ($vehicles->isEmpty()) {
            // Optional: Add a flash message or log if no available vehicles are found
            Log::warning("DrivingTestController@create: No available vehicles found.");
        }
        if ($admins->isEmpty()) {
            // Optional: Add a flash message or log if no admin users are found
            Log::warning("DrivingTestController@create: No users found with the 'Admin' role.");
        }


        return view('driving-tests.create', compact('candidates', 'vehicles', 'admins'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'interviewer_id' => 'required|exists:users,id', // Validate interviewer
            'vehicle_id' => 'required|exists:vehicles,id', // Validate vehicle_id instead of vehicle_type
            'test_date' => 'required|date|after_or_equal:now', // Use after_or_equal:now for flexibility
            'notes' => 'nullable|string' // Renamed 'route_details' to 'notes' to match view/validation? Check view. Let's assume 'notes' is correct field name in DB/Model
        ]);

        try {
            DB::beginTransaction();

            // Verify candidate eligibility again (defense in depth)
            $candidate = Candidate::findOrFail($validatedData['candidate_id']);
            if ($candidate->status !== Candidate::STATUS_TEST) {
                 throw new \Exception('Le candidat sélectionné n'est plus éligible pour un test de conduite.');
            }

            // Verify selected user is an Admin (defense in depth)
            $interviewer = User::findOrFail($validatedData['interviewer_id']);
             if (!$interviewer->hasRole('Admin')) { // Adjust role name if needed
                 throw new \Exception('L'utilisateur sélectionné n'est pas un examinateur valide.');
            }

            // Verify vehicle availability (defense in depth)
            $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);
            if ($vehicle->status !== 'available') {
                 throw new \Exception('Le véhicule sélectionné n'est pas disponible.');
            }

            // Check for existing scheduled test for the candidate
            $existingTest = $candidate->drivingTests()
                ->where('status', DrivingTest::STATUS_SCHEDULED)
                ->exists(); // More efficient check

            if ($existingTest) {
                throw new \Exception('Le candidat a déjà un test de conduite planifié.');
            }

             // Check for scheduling conflicts (Vehicle and Interviewer)
            $conflictingTest = DrivingTest::where('status', DrivingTest::STATUS_SCHEDULED)
                ->where('test_date', $validatedData['test_date'])
                ->where(function($query) use ($validatedData) {
                    $query->where('vehicle_id', $validatedData['vehicle_id'])
                          ->orWhere('interviewer_id', $validatedData['interviewer_id']);
                })
                ->exists();

            if ($conflictingTest) {
                 throw new \Exception('Conflit d'horaire: Le véhicule ou l'examinateur est déjà réservé à cette date/heure.');
            }


            // Create the driving test
            $test = DrivingTest::create([
                'candidate_id' => $validatedData['candidate_id'],
                'interviewer_id' => $validatedData['interviewer_id'], // Add interviewer_id
                'vehicle_id' => $validatedData['vehicle_id'],       // Add vehicle_id
                'test_date' => $validatedData['test_date'],
                'notes' => $validatedData['notes'], // Use notes from validation
                'status' => DrivingTest::STATUS_SCHEDULED
            ]);

            DB::commit();

            return redirect()->route('driving-tests.show', $test)
                ->with('success', 'Test de conduite planifié avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur création test de conduite: " . $e->getMessage());
            // Provide more specific user feedback
             $errorMessage = 'Erreur lors de la planification du test de conduite.';
             if ($e->getMessage() !== 'Erreur lors de la planification du test de conduite.') {
                 $errorMessage .= ' Détail : ' . $e->getMessage();
             }
             return back()
                 ->withInput()
                 ->with('error', $errorMessage);
        }
    }

    public function show(DrivingTest $drivingTest)
    {
        $drivingTest->load(['candidate', 'vehicle', 'interviewer', 'evaluation']); // Eager load relationships
        return view('driving-tests.show', compact('drivingTest'));
    }

    public function edit(DrivingTest $drivingTest)
    {
        if ($drivingTest->status !== DrivingTest::STATUS_SCHEDULED) {
            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('error', 'Seuls les tests planifiés peuvent être modifiés.');
        }

         // Fetch data needed for the edit form
        $candidates = Candidate::orderBy('last_name')->orderBy('first_name')->get(); // Get all candidates for edit potentially
        $vehicles = Vehicle::orderBy('make')->orderBy('model')->get(); // Get all vehicles
        $admins = User::whereHas('roles', function ($query) {
                         $query->where('name', 'Admin'); // Adjust role if needed
                     })->orderBy('name')->get();

        return view('driving-tests.edit', compact('drivingTest', 'candidates', 'vehicles', 'admins'));
    }

    public function update(Request $request, DrivingTest $drivingTest)
    {
        if ($drivingTest->status !== DrivingTest::STATUS_SCHEDULED) {
            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('error', 'Seuls les tests planifiés peuvent être modifiés.');
        }

        $validatedData = $request->validate([
            // Keep candidate_id potentially if you allow changing it during edit
             'candidate_id' => 'required|exists:candidates,id',
             'interviewer_id' => 'required|exists:users,id',
             'vehicle_id' => 'required|exists:vehicles,id',
             'test_date' => 'required|date|after_or_equal:now',
             'notes' => 'nullable|string'
        ]);

        try {
             DB::beginTransaction();

             // Add similar checks as in store() if necessary (e.g., interviewer role, vehicle availability, conflicts)

             $drivingTest->update($validatedData);

             DB::commit();

            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('success', 'Test de conduite mis à jour avec succès.');

        } catch (\Exception $e) {
             DB::rollBack();
            Log::error("Erreur mise à jour test de conduite (ID: {$drivingTest->id}): " . $e->getMessage());
             $errorMessage = 'Erreur lors de la mise à jour du test de conduite.';
             if ($e->getMessage() !== 'Erreur lors de la mise à jour du test de conduite.') {
                 $errorMessage .= ' Détail : ' . $e->getMessage();
             }
            return back()
                ->withInput()
                ->with('error', $errorMessage);
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

            // Update candidate status based on test outcome
            if ($validatedData['status'] === DrivingTest::STATUS_FAILED) {
                 $drivingTest->candidate->update(['status' => Candidate::STATUS_REFUSE]);
            } elseif ($validatedData['status'] === DrivingTest::STATUS_PASSED) {
                 // Optionally update candidate status to next step, e.g., 'OFFER'
                 // $drivingTest->candidate->update(['status' => Candidate::STATUS_OFFER]);
            }


            DB::commit();

            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('success', 'Statut du test de conduite mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour statut test de conduite (ID: {$drivingTest->id}): " . $e->getMessage());
             $errorMessage = 'Erreur lors de la mise à jour du statut du test.';
             if ($e->getMessage() !== 'Erreur lors de la mise à jour du statut du test.') {
                 $errorMessage .= ' Détail : ' . $e->getMessage();
             }
            return back()->with('error', $errorMessage);
        }
    }

    public function destroy(DrivingTest $drivingTest)
    {
         // Allow deletion only for scheduled or perhaps canceled tests?
        if (!in_array($drivingTest->status, [DrivingTest::STATUS_SCHEDULED, DrivingTest::STATUS_CANCELED])) {
             return back()->with('error', 'Seuls les tests planifiés ou annulés peuvent être supprimés.');
        }


        try {
            $drivingTest->delete();
            return redirect()->route('driving-tests.index')
                ->with('success', 'Test de conduite supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur suppression test de conduite (ID: {$drivingTest->id}): " . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression du test de conduite.');
        }
    }
}
