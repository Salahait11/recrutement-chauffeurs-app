<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;


class InterviewController extends Controller
{
     public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Interview::with(['candidate', 'scheduler', 'interviewer']);

            // Filtre par candidat
            if ($request->filled('candidate_id')) {
                $query->where('candidate_id', $request->input('candidate_id'));
            }

            // Filtre par type
            if ($request->filled('type')) {
                $query->where('type', $request->input('type'));
            }

            // Filtre par statut
            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            // Filtre par interviewer
            if ($request->filled('interviewer')) {
                $query->where('interviewer_id', $request->input('interviewer'));
            }

            // Filtre par date
            if ($request->filled('date_from')) {
                $query->where('interview_date', '>=', $request->input('date_from'));
            }
            if ($request->filled('date_to')) {
                $query->where('interview_date', '<=', $request->input('date_to'));
            }

            $interviews = $query->orderBy('interview_date', 'desc')->paginate(10)->withQueryString();
            
            // Log pour le débogage
            \Log::info('Nombre d\'entretiens trouvés : ' . $interviews->count());
            
            $interviewers = User::all();
            $candidates = \App\Models\Candidate::all();
            $types = ['initial', 'technique', 'final'];
            $statuses = ['planifié', 'en cours', 'terminé', 'annulé'];

            return view('interviews.index', compact('interviews', 'interviewers', 'candidates', 'types', 'statuses'));
        } catch (\Exception $e) {
            \Log::error('Erreur dans InterviewController@index : ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du chargement des entretiens.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $candidates = \App\Models\Candidate::all();
        $interviewers = User::all();
        $types = ['initial', 'technique', 'final'];
        $statuses = ['planifié', 'en cours', 'terminé', 'annulé'];

        return view('interviews.create', compact('candidates', 'interviewers', 'types', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'interview_date' => 'required|date',
            'type' => 'required|in:initial,technique,final',
            'notes' => 'nullable|string',
            'interviewer_id' => 'required|exists:users,id',
            'duration' => 'required|integer|min:15|max:240',
        ]);

        $interview = new Interview($validatedData);
        $interview->scheduler_id = auth()->id();
        $interview->status = 'planifié'; // Statut par défaut
        $interview->save();

        return redirect()->route('interviews.index')
            ->with('success', 'Entretien planifié avec succès.');
    }

    public function show(Interview $interview)
    {
        return view('interviews.show', compact('interview'));
    }

    public function edit(Interview $interview) {
        $candidates = \App\Models\Candidate::all();
        $interviewers = \App\Models\User::all();
        return view('interviews.edit', compact('interview', 'candidates', 'interviewers'));
    }

    public function update(Request $request, Interview $interview) {
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'interview_date' => 'required|date',
            'type' => 'required|in:initial,technique,final',
            'notes' => 'nullable|string',
            'interviewer_id' => 'nullable|exists:users,id',
            'duration' => 'required|integer|min:15|max:240',
            'status' => 'required|in:planifié,en cours,terminé,annulé',
        ]);
        $interview->update($validatedData);

        return redirect()->route('interviews.index')
            ->with('success', 'Entretien mis à jour avec succès.');
    }

    public function destroy(Interview $interview) {
         $interview->delete();

        return redirect()->route('interviews.index')
            ->with('success', 'Entretien supprimé avec succès.');
    }

    public function updateStatus(Request $request, Interview $interview)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:planifié,en cours,terminé,annulé',
        ]);

        $interview->update($validatedData);

        return redirect()->route('interviews.show', $interview)
            ->with('success', 'Statut de l\'entretien mis à jour avec succès.');
    }

    public function cancel(Interview $interview)
    {
        if ($interview->status === 'annulé' || $interview->status === 'évalué') {
            return redirect()->back()->with('error', 'Cet entretien ne peut pas être annulé.');
        }

        $interview->update([
            'status' => 'annulé'
        ]);

        return redirect()->route('interviews.show', $interview)
            ->with('success', 'L\'entretien a été annulé avec succès.');
    }

    public function generatePdf(Request $request)
    {
        try {
            $query = Interview::with(['candidate', 'scheduler', 'interviewer']);

            // Initialiser les filtres
            $filters = [];

            if ($request->filled('candidate_id')) {
                $query->where('candidate_id', $request->input('candidate_id'));
                $filters['candidate_id'] = $request->input('candidate_id');
            }

            if ($request->filled('type')) {
                $query->where('type', $request->input('type'));
                $filters['type'] = $request->input('type');
            }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
                $filters['status'] = $request->input('status');
            }

            if ($request->filled('interviewer')) {
                $query->where('interviewer_id', $request->input('interviewer'));
                $filters['interviewer'] = $request->input('interviewer');
            }

            if ($request->filled('date_from')) {
                $query->where('interview_date', '>=', $request->input('date_from'));
                $filters['date_from'] = $request->input('date_from');
            }

            if ($request->filled('date_to')) {
                $query->where('interview_date', '<=', $request->input('date_to'));
                $filters['date_to'] = $request->input('date_to');
            }

            $interviews = $query->orderBy('interview_date', 'desc')->get();

            $pdf = Pdf::loadView('interviews.pdf', [
                'interviews' => $interviews,
                'filters' => $filters
            ]);

            return $pdf->download('entretiens.pdf');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la génération du PDF des entretiens : ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la génération du PDF.');
        }
    }

}
