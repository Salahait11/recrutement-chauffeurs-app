<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = Candidate::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
            Log::debug("Recherche Candidat - Filtre appliqué: $search");
        }

        // Filtre par statut
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Liste des statuts pour le filtre
        $statuses = [
            Candidate::STATUS_NOUVEAU,
            Candidate::STATUS_CONTACTE,
            Candidate::STATUS_ENTRETIEN,
            Candidate::STATUS_TEST,
            Candidate::STATUS_OFFRE,
            Candidate::STATUS_EMBAUCHE,
            Candidate::STATUS_REFUSE
        ];

        $candidates = $query->latest()->paginate(10);

        return view('candidates.index', compact('candidates', 'statuses'));
    }

    public function create()
    {
        return view('candidates.create');
    }

   public function store(Request $request)
{
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:candidates,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'birth_date' => 'required|date',
        'driving_license_number' => 'required|string|max:50|unique:candidates,driving_license_number',
        'driving_license_expiry' => 'required|date|after:today',
        'years_of_experience' => 'required|integer|min:0',
        'status' => 'required|string|in:' . implode(',', array_keys(Candidate::$statuses)),
        'notes' => 'nullable|string',
    ]);

    try {
        DB::transaction(function () use ($validatedData) {
            Candidate::create($validatedData);
        });

        return redirect()->route('candidates.index')->with('success', 'Candidat ajouté avec succès.');

    } catch (\Exception $e) {
        Log::error('Erreur lors de l’ajout du candidat : ' . $e->getMessage());
        return back()->with('error', 'Erreur lors de l’ajout du candidat.');
    }
}

    public function show(Candidate $candidate)
    {
        $candidate->load(['interviews', 'drivingTests', 'offers', 'documents']);
        $statuses = Candidate::$statuses;
        return view('candidates.show', compact('candidate', 'statuses'));
    }

    public function edit(Candidate $candidate)
    {
        $statuses = Candidate::$statuses;
        return view('candidates.edit', compact('candidate','statuses'));
    }

   public function update(Request $request, Candidate $candidate)
{
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:candidates,email,' . $candidate->id,
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'birth_date' => 'required|date',
        'driving_license_number' => 'required|string|max:50|unique:candidates,driving_license_number,' . $candidate->id,
        'driving_license_expiry' => 'required|date|after:today',
        'years_of_experience' => 'required|integer|min:0',
        'status' => 'required|string|in:' . implode(',', array_keys(Candidate::$statuses)),
        'notes' => 'nullable|string',
    ]);

    try {
        DB::transaction(function () use ($candidate, $validatedData) {
            $candidate->update($validatedData);
        });

        return redirect()->route('candidates.index')->with('success', 'Candidat mis à jour avec succès.');

    } catch (\Exception $e) {
        Log::error('Erreur lors de la mise à jour du candidat : ' . $e->getMessage());
        return back()->with('error', 'Erreur lors de la mise à jour du candidat.');
    }
}

    public function destroy(Candidate $candidate)
    {
        try {
            if ($candidate->status === Candidate::STATUS_EMBAUCHE) {
                return back()->with('error', 'Impossible de supprimer un candidat embauché.');
            }

            $candidate->delete();

            return redirect()->route('candidates.index')
                ->with('success', 'Candidat supprimé avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur suppression candidat: " . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression du candidat.');
        }
    }
}
