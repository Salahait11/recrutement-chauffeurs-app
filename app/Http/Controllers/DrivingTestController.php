<?php

namespace App\Http\Controllers;

use App\Models\DrivingTest;
use App\Models\Candidate;
// use App\Models\Employee; // Seems unused in the provided context
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// Assuming Spatie/Permission for roles: use User::role() or $user->hasRole()
// use Spatie\Permission\Models\Role; // Not directly used if checking via User model

class DrivingTestController extends Controller
{
    public function index(Request $request)
    {
        $query = DrivingTest::with(['candidate', 'vehicle', 'interviewer']);

        if ($request->filled('status')) { // Use filled() for better check
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date')) {
            $query->whereDate('test_date', $request->input('date'));
        }

        $drivingTests = $query->latest()->paginate(10);

        return view('driving-tests.index', compact('drivingTests'));
    }

    public function create()
    {
        $candidates = Candidate::whereIn('status', [Candidate::STATUS_TEST]) // Adjust status if needed
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $vehicles = Vehicle::where('status', 'available') // Assuming 'available' status
                            ->orderBy('make')
                            ->orderBy('model')
                            ->get();

        // Assuming Spatie/Permission: Fetch users with the 'Admin' role
        $admins = User::role('Admin')->orderBy('name')->get(); // Adjust role name if needed

        // Log warnings if essential data is missing
        if ($candidates->isEmpty()) {
            Log::warning("DrivingTestController@create: No candidates found with status 'test'.");
            // Optionally flash a user-facing warning
            // session()->flash('warning', 'Aucun candidat éligible (statut test) trouvé.');
        }
         if ($vehicles->isEmpty()) {
            Log::warning("DrivingTestController@create: No available vehicles found.");
            // session()->flash('warning', 'Aucun véhicule disponible trouvé.');
        }
        if ($admins->isEmpty()) {
            Log::warning("DrivingTestController@create: No users found with the 'Admin' role.");
             // session()->flash('warning', 'Aucun examinateur (Admin) trouvé.');
        }

        return view('driving-tests.create', compact('candidates', 'vehicles', 'admins'));
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
            // Ensure candidate status allows testing (adjust array if needed)
            if (!in_array($candidate->status, [Candidate::STATUS_TEST])) {
                 // FIXED: Escaped quote
                 throw new \Exception('Le candidat sélectionné n est plus éligible pour un test de conduite.');
            }

            $interviewer = User::findOrFail($validatedData['interviewer_id']);
            // Ensure interviewer has the correct role (adjust role name if needed)
            if (!$interviewer->hasRole('Admin')) {
                 // FIXED: Escaped quote
                 throw new \Exception('L utilisateur sélectionné n est pas un examinateur valide.');
            }

            $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);
            if ($vehicle->status !== 'available') { // Ensure vehicle is available
                 // FIXED: Escaped quote
                 throw new \Exception('Le véhicule sélectionné n est pas disponible.');
            }

            // Check if candidate already has a test scheduled
            $existingTest = DrivingTest::where('candidate_id', $validatedData['candidate_id'])
                ->where('status', DrivingTest::STATUS_SCHEDULED)
                ->exists();
            if ($existingTest) {
                throw new \Exception('Le candidat a déjà un test de conduite planifié.'); // No quote here
            }

            // Check for vehicle/interviewer schedule conflicts
            $conflictingTest = DrivingTest::where('status', DrivingTest::STATUS_SCHEDULED)
                ->where('test_date', $validatedData['test_date'])
                ->where(function($query) use ($validatedData) {
                    $query->where('vehicle_id', $validatedData['vehicle_id'])
                          ->orWhere('interviewer_id', $validatedData['interviewer_id']);
                })
                ->exists();
            if ($conflictingTest) {
                 // FIXED: Escaped quotes
                 throw new \Exception('Conflit d horaire: Le véhicule ou l examinateur est déjà réservé à cette date/heure.');
            }

            $test = DrivingTest::create([
                'candidate_id' => $validatedData['candidate_id'],
                'interviewer_id' => $validatedData['interviewer_id'],
                'vehicle_id' => $validatedData['vehicle_id'],
                'test_date' => $validatedData['test_date'],
                'notes' => $validatedData['notes'],
                'status' => DrivingTest::STATUS_SCHEDULED // Assuming this is the initial status constant
            ]);

            DB::commit();

            return redirect()->route('driving-tests.show', $test)
                ->with('success', 'Test de conduite planifié avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur création test de conduite: " . $e->getMessage());

            // Prepare user-friendly error message (using the escaped messages)
            $errorMessage = 'Erreur lors de la planification du test de conduite.';
             $knownMessages = [
                'Le candidat sélectionné n est plus éligible pour un test de conduite.',
                'L utilisateur sélectionné n est pas un examinateur valide.',
                'Le véhicule sélectionné n est pas disponible.',
                'Le candidat a déjà un test de conduite planifié.',
                'Conflit d horaire: Le véhicule ou l examinateur est déjà réservé à cette date/heure.'
             ];
            if (in_array($e->getMessage(), $knownMessages)) {
                 $errorMessage = $e->getMessage(); // Pass the already escaped message
            }
            // Avoid showing raw internal messages directly for unexpected errors

             return back()
                 ->withInput()
                 ->with('error', $errorMessage);
        }
    }

    public function show(DrivingTest $drivingTest)
    {
        // Eager load related models for efficiency
        $drivingTest->load(['candidate', 'vehicle', 'interviewer', 'evaluation']);
        return view('driving-tests.show', compact('drivingTest'));
    }

    public function edit(DrivingTest $drivingTest)
    {
        // Allow editing only for scheduled tests
        if ($drivingTest->status !== DrivingTest::STATUS_SCHEDULED) {
            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('error', 'Seuls les tests planifiés peuvent être modifiés.');
        }

        // Fetch necessary data for the form dropdowns
        $candidates = Candidate::orderBy('last_name')->orderBy('first_name')->get(); // Consider filtering?
        $vehicles = Vehicle::where('status', 'available')->orderBy('make')->orderBy('model')->get(); // Fetch available vehicles for edit too
        $admins = User::role('Admin')->orderBy('name')->get(); // Adjust role if needed

        return view('driving-tests.edit', compact('drivingTest', 'candidates', 'vehicles', 'admins'));
    }

    public function update(Request $request, DrivingTest $drivingTest)
    {
        if ($drivingTest->status !== DrivingTest::STATUS_SCHEDULED) {
            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('error', 'Seuls les tests planifiés peuvent être modifiés.');
        }

        $validatedData = $request->validate([
             // Decide if candidate should be updatable here. If so, add validation.
             // 'candidate_id' => 'required|exists:candidates,id',
             'interviewer_id' => 'required|exists:users,id',
             'vehicle_id' => 'required|exists:vehicles,id',
             'test_date' => 'required|date|after_or_equal:now',
             'notes' => 'nullable|string'
        ]);

        try {
             DB::beginTransaction();

             $interviewer = User::findOrFail($validatedData['interviewer_id']);
             if (!$interviewer->hasRole('Admin')) {
                 // FIXED: Escaped quote
                 throw new \Exception('L examinateur sélectionné n est plus valide.');
             }
             $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);
             // Re-check availability if necessary, especially if it could change
             if ($vehicle->status !== 'available') {
                  // FIXED: Escaped quote
                  throw new \Exception('Le véhicule sélectionné n est plus disponible.');
             }

             // Check for conflicts, excluding the current test
             $conflictingTest = DrivingTest::where('status', DrivingTest::STATUS_SCHEDULED)
                 ->where('id', '!=', $drivingTest->id) // Exclude self
                 ->where('test_date', $validatedData['test_date'])
                 ->where(function($query) use ($validatedData) {
                     $query->where('vehicle_id', $validatedData['vehicle_id'])
                           ->orWhere('interviewer_id', $validatedData['interviewer_id']);
                 })
                 ->exists();
             if ($conflictingTest) {
                  // FIXED: Escaped quotes
                  throw new \Exception('Conflit d horaire: Le véhicule ou l examinateur est déjà réservé à cette date/heure pour un autre test.');
             }

             $drivingTest->update($validatedData);

             DB::commit();

            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('success', 'Test de conduite mis à jour avec succès.');

        } catch (\Exception $e) {
             DB::rollBack();
             Log::error("Erreur mise à jour test de conduite (ID: {$drivingTest->id}): " . $e->getMessage());

             $errorMessage = 'Erreur lors de la mise à jour du test de conduite.';
              $knownMessages = [
                 'L examinateur sélectionné n est plus valide.',
                 'Le véhicule sélectionné n est plus disponible.',
                 'Conflit d horaire: Le véhicule ou l examinateur est déjà réservé à cette date/heure pour un autre test.'
              ];
             if (in_array($e->getMessage(), $knownMessages)) {
                 $errorMessage = $e->getMessage(); // Pass escaped message
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
                DrivingTest::STATUS_CANCELED, // Ensure these constants exist in DrivingTest model
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

            // Update related Candidate status if test failed or passed
            if ($validatedData['status'] === DrivingTest::STATUS_FAILED) {
                 // Assuming Candidate::STATUS_REFUSE exists
                 $drivingTest->candidate->update(['status' => Candidate::STATUS_REFUSE]);
            } elseif ($validatedData['status'] === DrivingTest::STATUS_PASSED) {
                 // Optionally update candidate status, e.g., to 'offer' or 'hired'
                 // Assuming Candidate::STATUS_OFFER exists
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
         // Define which statuses allow deletion
        $allowedStatuses = [DrivingTest::STATUS_SCHEDULED, DrivingTest::STATUS_CANCELED];
        if (!in_array($drivingTest->status, $allowedStatuses)) {
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
