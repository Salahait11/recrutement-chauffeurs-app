<?php

namespace App\Http\Controllers;

// Core Models
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\User;
use App\Models\Absence; 

// Laravel Facades & Classes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Pour transactions
use Illuminate\Validation\Rules\File;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * Adapte l'affichage selon le rôle.
     */
    public function index(Request $request) // Ajout Request pour filtre éventuel
    {
        $user = Auth::user()->loadMissing('employee');
        $query = LeaveRequest::with(['employee.user', 'leaveType', 'approver'])
                             ->orderBy('start_date', 'desc');

        // !! Logique de rôle simplifiée - À adapter !!
        $isAdminOrManager = $user->isAdmin(); // || $user->hasRole('rh_manager') || $user->isManager();

        if (!$isAdminOrManager) {
           if($user->employee) {
               $query->where('employee_id', $user->employee->id);
           } else {
               $query->whereRaw('1 = 0'); // Ne rien montrer
           }
        }

        // Appliquer filtre si présent (pourrait être utilisé par l'admin)
        if ($employee_id = $request->query('employee_id')) {
             $query->where('employee_id', $employee_id);
        }
        if ($status = $request->query('status')) {
            if (in_array($status, ['pending', 'approved', 'rejected', 'canceled'])) {
                 $query->where('status', $status);
            }
        }

        $leaveRequests = $query->paginate(20)->withQueryString(); // Garde les paramètres de filtre dans la pagination

        // Récupérer employés pour le filtre dans la vue index aussi
        $employees = $isAdminOrManager ? Employee::with('user')->where('status', 'active')->get()->sortBy('user.name') : collect();


        return view('leave_requests.index', compact('leaveRequests', 'employees')); // Passe aussi $employees
    }

    /**
     * Show the form for creating a new resource.
     * Permet à l'admin/manager de choisir l'employé.
     */
    public function create()
    {
        $user = Auth::user()->loadMissing('employee');
        $isAdminOrManager = $user->isAdmin(); // || ... adapter

        $employees = null;
        $employee = null;

        if ($isAdminOrManager) {
            $employees = Employee::with('user')
                                 ->whereHas('user')
                                 ->where('status', 'active')
                                 ->get()
                                 ->sortBy('user.name');
        } elseif ($user->employee) {
            $employee = $user->employee;
        } else {
             return Redirect::route('dashboard')->with('error', 'Action non autorisée.');
        }

        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

        return view('leave_requests.create', compact('employee', 'employees', 'leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
            'attachment' => ['nullable', File::types(['pdf', 'jpg', 'png', 'jpeg'])->max(2 * 1024) ],
        ], [ /* ... messages ... */ ]);

        // Calcul durée (simple) - à affiner si besoin
        try {
           $start = Carbon::parse($validatedData['start_date']);
           $end = Carbon::parse($validatedData['end_date']);
           $validatedData['duration_days'] = $start->diffInDays($end) + 1;
        } catch (\Exception $e) { return Redirect::back()->withInput()->with('error', 'Format date invalide.'); }

        // Gérer l'upload
        $filePath = null;
        if ($request->hasFile('attachment')) {
            try {
               $file = $request->file('attachment');
               $filePath = $file->store('leave_attachments/' . $validatedData['employee_id'], 'public');
               $validatedData['attachment_path'] = $filePath;
            } catch (\Exception $e) { Log::error(...); return Redirect::back()->withInput()->with('error', 'Erreur stockage justificatif.'); }
        }

        $validatedData['status'] = 'pending'; // Statut initial

        try {
           LeaveRequest::create($validatedData);
        } catch (\Exception $e) {
            if ($filePath) Storage::disk('public')->delete($filePath);
            Log::error(...); return Redirect::back()->withInput()->with('error', 'Erreur soumission demande.');
        }

        // Notification ?

        return Redirect::route('leave-requests.index')->with('success', 'Demande de congé soumise.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        // !! Logique d'autorisation !!
        // $this->authorize('view', $leaveRequest);

        $leaveRequest->load(['employee.user', 'leaveType', 'approver']);
        return view('leave_requests.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        // !! Logique d'autorisation !!
        // $this->authorize('update', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Demande non modifiable.');
        }
        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();
        $employee = $leaveRequest->employee; // Récupérer l'employé concerné
        // Récupérer la liste des employés si c'est un admin qui modifie ? Moins courant.
        $employees = null;
        if (Auth::user()->isAdmin()) {
             $employees = Employee::with('user')->where('status', 'active')->get()->sortBy('user.name');
        }

        return view('leave_requests.edit', compact('leaveRequest', 'leaveTypes', 'employee', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     * Gère approbation/rejet OU modification par employé (si statut pending).
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        // Scénario 1: Approbation/Rejet par admin/manager
        if ($request->has('action') && $leaveRequest->status === 'pending') {
            // !! Autorisation spécifique pour approuver/rejeter !!
            // $this->authorize('approveReject', $leaveRequest);
            $action = $request->input('action');
            if ($action === 'approve') {
                DB::beginTransaction(); try { /* ... logique approbation ... */ DB::commit(); return Redirect::route('leave-requests.show', $leaveRequest->id)->with('success', 'Demande approuvée.'); } catch (\Exception $e) { DB::rollBack(); Log::error(...); return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Erreur approbation.'); }
            } elseif ($action === 'reject') {
                 $request->validate(['approver_comment' => 'required|string|max:500'], ['approver_comment.required' => 'Commentaire requis pour rejet.']);
                 DB::beginTransaction(); try { /* ... logique rejet ... */ DB::commit(); return Redirect::route('leave-requests.show', $leaveRequest->id)->with('success', 'Demande rejetée.'); } catch (\Exception $e) { DB::rollBack(); Log::error(...); return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Erreur rejet.'); }
            }
        }
        // Scénario 2: Modification par l'employé (ou admin) si statut pending
        elseif ($leaveRequest->status === 'pending') {
             // !! Autorisation pour modifier sa propre demande en attente !!
             // $this->authorize('updateOwnPending', $leaveRequest);

             $validatedData = $request->validate([ /* ... règles similaires à store MAIS sans employee_id ? ... */ ]);
             // Gérer le fichier joint si modifié...
             // $leaveRequest->update($validatedData);
             return Redirect::route('leave-requests.show', $leaveRequest->id)->with('success', 'Demande mise à jour (Logique à finaliser).');
        }

        // Si aucune action ou statut non modifiable
        return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Action non valide ou demande déjà traitée.');
    }


    /**
     * Remove the specified resource from storage. (Utilisé pour Annuler par l'employé/admin)
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
         // !! Logique d'autorisation !!
         // $this->authorize('cancel', $leaveRequest);

         if ($leaveRequest->status === 'pending') {
             try {
                 $leaveRequest->status = 'canceled';
                 $leaveRequest->save();
                 // Renvoyer crédit ? (non implémenté)
                 return Redirect::route('leave-requests.index')->with('success', 'Demande annulée.');
             } catch (\Exception $e) { Log::error(...); return Redirect::route('leave-requests.index')->with('error', 'Erreur annulation.'); }
         } else {
              return Redirect::route('leave-requests.index')->with('error', 'Seules les demandes en attente peuvent être annulées.');
         }
    }

    /**
     * Récupère les événements pour FullCalendar.
     */
    public function getLeaveEvents(Request $request): JsonResponse
    {
        $request->validate([
            'start' => 'sometimes|date',
            'end' => 'sometimes|date|after_or_equal:start',
            'employee_id' => 'sometimes|integer|exists:employees,id' // Valide filtre optionnel
        ]);
        $start = $request->query('start', Carbon::now()->subYear()->toDateString());
        $end = $request->query('end', Carbon::now()->addYear()->toDateString());

        // Requête Congés
        $queryLeave = LeaveRequest::with(['employee.user', 'leaveType'])
                                 ->where('status', 'approved')
                                 ->where(function($q) use ($start, $end) { $q->where('start_date', '<=', $end)->where('end_date', '>=', $start); });

        // Requête Absences
        $queryAbsence = Absence::with(['employee.user']) // Charger relations absence
                             ->where('absence_date', '>=', $start)
                             ->where('absence_date', '<=', $end);

        // Appliquer le filtre employé si présent
        if ($request->filled('employee_id')) {
             $employeeId = $request->input('employee_id');
             // !! Ajouter vérification permission de voir cet employé si l'utilisateur n'est pas admin !!
             $queryLeave->where('employee_id', $employeeId);
             $queryAbsence->where('employee_id', $employeeId);
        }

        $leaveRequests = $queryLeave->get();
        $absences = $queryAbsence->get();

        // Formatage Congés
        $leaveEvents = $leaveRequests->map(function ($leave) { /* ... comme avant ... */ });
        // Formatage Absences
        $absenceEvents = $absences->map(function ($absence) { /* ... comme avant ... */ });

        // Fusion
        $allEvents = $leaveEvents->merge($absenceEvents);
        return response()->json($allEvents);
    }

     /**
     * Display the leave calendar view.
     */
    public function calendar()
    {
         // Récupérer les employés pour le filtre du calendrier
         $employees = Employee::with('user')->where('status', 'active')->get()->sortBy('user.name');
         return view('calendar.index', compact('employees'));
    }
}