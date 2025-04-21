<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;


class InterviewController extends Controller
{
     public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interviews = Interview::with(['candidate', 'scheduler','interviewer'])
        ->get();

        return view('interviews.index', compact('interviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('interviews.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'interview_date' => 'required|date|after:today',
            'type' => 'required|in:initial,technique,final',
            'notes' => 'nullable|string',
            'interviewer_id' => 'nullable|exists:users,id',

        ]);
        $interview = new Interview($validatedData);
        $interview->scheduler_id = auth()->id();
        $interview->status = 'planifié';
        $interview->save();

        return redirect()->route('interviews.index')
            ->with('success', 'Entretien planifié avec succès.');

    }

    public function show(Interview $interview)
    {
        return view('interviews.show', compact('interview'));
    }

    public function edit(Interview $interview) {
        return view('interviews.edit', compact('interview'));
    }

    public function update(Request $request, Interview $interview) {
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'interview_date' => 'required|date|after:today',
            'type' => 'required|in:initial,technique,final',
            'notes' => 'nullable|string',
            'interviewer_id' => 'nullable|exists:users,id',
            'result' => 'nullable|string',
            'feedback' => 'nullable|string',
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
}
