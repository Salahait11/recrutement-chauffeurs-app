<?php

namespace App\Http\Controllers;

use App\Models\DrivingTest;
use App\Models\Candidate;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DrivingTestController extends Controller
{
    public function index(Request $request)
    {
        $query = DrivingTest::with('candidate');
        
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
        $candidates = Candidate::where('status', Candidate::STATUS_EN_COURS)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $evaluators = Employee::all();

        $vehicles = DrivingTest::getVehicleTypes();

        return view('driving-tests.create', compact('candidates', 'evaluators', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'test_date' => 'required|date|after:today',
            'vehicle_type' => 'required|string|max:50',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Vérifier si le candidat peut passer un test
            $candidate = Candidate::findOrFail($validatedData['candidate_id']);
            
            if ($candidate->status !== Candidate::STATUS_EN_COURS) {
                throw new \Exception('Le candidat doit être en cours de recrutement pour passer un test de conduite.');
            }

            // Vérifier si le candidat a déjà un test planifié
            $existingTest = $candidate->drivingTests()
                ->where('status', 'planifie')
                ->first();

            if ($existingTest) {
                throw new \Exception('Le candidat a déjà un test de conduite planifié.');
            }

            // Créer le test
            $test = DrivingTest::create([
                'candidate_id' => $validatedData['candidate_id'],
                'test_date' => $validatedData['test_date'],
                'vehicle_type' => $validatedData['vehicle_type'],
                'notes' => $validatedData['notes'],
                'status' => 'planifie'
            ]);

            DB::commit();

            return redirect()->route('driving-tests.show', $test)
                ->with('success', 'Test de conduite planifié avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur création test de conduite: " . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(DrivingTest $drivingTest)
    {
        $drivingTest->load('candidate');
        return view('driving-tests.show', compact('drivingTest'));
    }

    public function edit(DrivingTest $drivingTest)
    {
        if ($drivingTest->status !== 'planifie') {
            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('error', 'Seuls les tests planifiés peuvent être modifiés.');
        }

        return view('driving-tests.edit', compact('drivingTest'));
    }

    public function update(Request $request, DrivingTest $drivingTest)
    {
        if ($drivingTest->status !== 'planifie') {
            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('error', 'Seuls les tests planifiés peuvent être modifiés.');
        }

        $validatedData = $request->validate([
            'test_date' => 'required|date|after:today',
            'vehicle_type' => 'required|string|max:50',
            'notes' => 'nullable|string'
        ]);

        try {
            $drivingTest->update($validatedData);

            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('success', 'Test de conduite mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur mise à jour test de conduite: " . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du test de conduite.');
        }
    }

    public function updateStatus(Request $request, DrivingTest $drivingTest)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:reussi,echoue,annule',
            'feedback' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            if ($drivingTest->status !== 'planifie') {
                throw new \Exception('Seuls les tests planifiés peuvent être complétés ou annulés.');
            }

            $drivingTest->update([
                'status' => $validatedData['status'],
                'feedback' => $validatedData['feedback']
            ]);

            // Si le test est échoué, mettre à jour le statut du candidat à "refusé"
            if ($validatedData['status'] === 'echoue') {
                $drivingTest->candidate->update(['status' => Candidate::STATUS_REFUSE]);
            }

            DB::commit();

            return redirect()->route('driving-tests.show', $drivingTest)
                ->with('success', 'Statut du test de conduite mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour statut test de conduite: " . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(DrivingTest $drivingTest)
    {
        if ($drivingTest->status !== 'planifie') {
            return back()->with('error', 'Seuls les tests planifiés peuvent être supprimés.');
        }

        try {
            $drivingTest->delete();
            return redirect()->route('driving-tests.index')
                ->with('success', 'Test de conduite supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur suppression test de conduite: " . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression du test de conduite.');
        }
    }
}