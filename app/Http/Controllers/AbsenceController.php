<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Employee; // Pour la liste dans create/edit
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class AbsenceController extends Controller
{
     // Pas besoin de constructeur, middleware sur la route

    /** Display a listing of absences. */
    public function index(Request $request) // Ajout Request pour filtres futurs
    {
        // TODO: Ajouter filtres par employé, date, type...
        $query = Absence::with(['employee.user', 'recorder'])
                       ->orderBy('absence_date', 'desc');

        // Exemple de filtre simple (à améliorer)
        if ($employee_id = $request->query('employee_id')) {
            $query->where('employee_id', $employee_id);
        }

        $absences = $query->paginate(20);
        $employees = Employee::with('user')->where('status', 'active')->get()->sortBy('user.name'); // Pour le filtre

        // Utilise la vue dans admin/absences
        return view('admin.absences.index', compact('absences', 'employees'));
    }

    /** Show the form for creating a new absence. */
    public function create()
    {
         // Récupérer les employés actifs pour la sélection
        $employees = Employee::with('user')->where('status', 'active')->get()->sortBy('user.name');
        // Types/motifs possibles (peut venir d'une config ou rester libre)
        $reasonTypes = ['Maladie', 'Injustifiée', 'Retard', 'Départ Anticipé', 'Formation', 'Autre'];

         // Utilise la vue dans admin/absences
        return view('admin.absences.create', compact('employees', 'reasonTypes'));
    }

    /** Store a newly created absence in storage. */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'absence_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i', // Format HH:MM
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'reason_type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'is_justified' => 'required|boolean',
        ]);

        // Ajouter qui a enregistré l'absence
        $validatedData['recorded_by_id'] = Auth::id();
        // Convertir booléen
        $validatedData['is_justified'] = (bool) $validatedData['is_justified'];

        try {
            Absence::create($validatedData);
             return Redirect::route('admin.absences.index')->with('success', 'Absence enregistrée.');
        } catch (\Exception $e) {
             Log::error("Erreur création absence: " . $e->getMessage());
             return Redirect::back()->withInput()->with('error', 'Erreur enregistrement absence.');
        }
    }

    /** Display the specified resource. */
    public function show(Absence $absence)
    {
        // Normalement pas très utile, on redirige vers edit ou index
         return redirect()->route('admin.absences.edit', $absence->id);
    }

    /** Show the form for editing the specified resource. */
    public function edit(Absence $absence)
    {
        $absence->load('employee.user'); // Charger l'employé pour affichage
        $employees = Employee::with('user')->where('status', 'active')->get()->sortBy('user.name');
        $reasonTypes = ['Maladie', 'Injustifiée', 'Retard', 'Départ Anticipé', 'Formation', 'Autre'];

        // Utilise la vue dans admin/absences
        return view('admin.absences.edit', compact('absence', 'employees', 'reasonTypes'));
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, Absence $absence)
    {
         $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'absence_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'reason_type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'is_justified' => 'required|boolean',
        ]);

        // L'enregistreur initial reste le même
        $validatedData['is_justified'] = (bool) $validatedData['is_justified'];

        try {
            $absence->update($validatedData);
            return Redirect::route('admin.absences.index')->with('success', 'Absence mise à jour.');
        } catch (\Exception $e) {
             Log::error("Erreur MAJ absence ID {$absence->id}: " . $e->getMessage());
             return Redirect::back()->withInput()->with('error', 'Erreur mise à jour absence.');
        }
    }

    /** Remove the specified resource from storage. */
    public function destroy(Absence $absence)
    {
         try {
            $absence->delete();
            return Redirect::route('admin.absences.index')->with('success', 'Absence supprimée.');
         } catch (\Exception $e) {
             Log::error("Erreur suppression absence ID {$absence->id}: " . $e->getMessage());
             return Redirect::route('admin.absences.index')->with('error', 'Erreur suppression absence.');
         }
    }
}