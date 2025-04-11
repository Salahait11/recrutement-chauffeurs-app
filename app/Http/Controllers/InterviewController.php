<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InterviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Interview::with('candidate');
        
        $statusFilter = $request->input('status');
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        
        $dateFromFilter = $request->input('date_from');
        if ($dateFromFilter) {
            $query->whereDate('interview_date', '>=', $dateFromFilter);
        }

        $dateToFilter = $request->input('date_to');
        if ($dateToFilter) {
            $query->whereDate('interview_date', '<=', $dateToFilter);
        }

        $candidateFilter = $request->input('candidate_id');
        if ($candidateFilter) {
            $query->where('candidate_id', $candidateFilter);
        }
        
        $interviews = $query->latest()->paginate(10);
        
        $candidates = Candidate::orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $statuses = Interview::getStatuses();
        
        return view('interviews.index', compact(
            'interviews',
            'candidates',
            'statuses',
            'statusFilter',
            'dateFromFilter',
            'dateToFilter',
            'candidateFilter'
        ));
    }

    public function create()
    {
        $candidates = Candidate::whereIn('status', [
                Candidate::STATUS_NOUVEAU,
                Candidate::STATUS_CONTACTE,
                Candidate::STATUS_ENTRETIEN
            ])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $types = Interview::getTypes();

        // Récupérer les utilisateurs qui peuvent être intervieweurs
        $interviewers = User::where('role', 'interviewer')
            ->orWhere('role', 'admin')
            ->orderBy('name')
            ->get();

        return view('interviews.create', compact('candidates', 'types', 'interviewers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'interview_date' => 'required|date|after:today',
            'type' => 'required|in:' . implode(',', Interview::getTypes()),
            'notes' => 'nullable|string'
        ]);

        $interview = new Interview($validatedData);
        $interview->scheduler_id = auth()->id();
        $interview->status = Interview::STATUS_PLANIFIE;
        $interview->save();

        // Mise à jour du statut du candidat
        $candidate = Candidate::find($validatedData['candidate_id']);
        $candidate->status = Candidate::STATUS_ENTRETIEN;
        $candidate->save();

        return redirect()->route('interviews.index')
            ->with('success', 'Entretien planifié avec succès.');
    }

    public function show(Interview $interview)
    {
        $interview->load('candidate');
        return view('interviews.show', compact('interview'));
    }

    public function edit(Interview $interview)
    {
        if ($interview->status !== 'planifie') {
            return redirect()->route('interviews.show', $interview)
                ->with('error', 'Seuls les entretiens planifiés peuvent être modifiés.');
        }

        return view('interviews.edit', compact('interview'));
    }

    public function update(Request $request, Interview $interview)
    {
        if ($interview->status !== 'planifie') {
            return redirect()->route('interviews.show', $interview)
                ->with('error', 'Seuls les entretiens planifiés peuvent être modifiés.');
        }

        $validatedData = $request->validate([
            'interview_date' => 'required|date|after:today',
            'type' => 'required|in:initial,technique,final',
            'notes' => 'nullable|string'
        ]);

        try {
            $interview->update($validatedData);

            return redirect()->route('interviews.show', $interview)
                ->with('success', 'Entretien mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur mise à jour entretien: " . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de l\'entretien.');
        }
    }

    public function updateStatus(Request $request, Interview $interview)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:complete,annule',
            'result' => 'required_if:status,complete|in:positif,negatif',
            'feedback' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            if ($interview->status !== 'planifie') {
                throw new \Exception('Seuls les entretiens planifiés peuvent être complétés ou annulés.');
            }

            $interview->update([
                'status' => $validatedData['status'],
                'result' => $validatedData['result'] ?? null,
                'feedback' => $validatedData['feedback']
            ]);

            // Si l'entretien est complété avec un résultat négatif
            if ($validatedData['status'] === 'complete' && $validatedData['result'] === 'negatif') {
                // Mettre à jour le statut du candidat à "refusé"
                $interview->candidate->update(['status' => Candidate::STATUS_REFUSE]);
            }

            DB::commit();

            return redirect()->route('interviews.show', $interview)
                ->with('success', 'Statut de l\'entretien mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour statut entretien: " . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Interview $interview)
    {
        if ($interview->status !== 'planifie') {
            return back()->with('error', 'Seuls les entretiens planifiés peuvent être supprimés.');
        }

        try {
            $interview->delete();
            return redirect()->route('interviews.index')
                ->with('success', 'Entretien supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur suppression entretien: " . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression de l\'entretien.');
        }
    }
}
