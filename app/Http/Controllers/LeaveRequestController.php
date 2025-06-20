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
use Illuminate\Support\Facades\DB; // Gardé pour transactions approve/reject
use Illuminate\Validation\Rules\File;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user()->loadMissing('employee');
        $query = LeaveRequest::with(['employee.user', 'leaveType', 'approver'])
                             ->orderBy('start_date', 'desc');

        // -- Détermination du rôle (simplifié) --
        $isAdminOrManager = $user->isAdmin();

        // --- Gérer les filtres de la requête ---
        $employeeFilter = $request->query('employee_id');
        $statusFilter = $request->query('status');
        $dateFromFilter = $request->query('date_from'); // Date début période
        $dateToFilter = $request->query('date_to');   // Date fin période

        // Appliquer filtre Employé (si admin/manager)
        if ($isAdminOrManager && $employeeFilter) {
            $query->where('employee_id', $employeeFilter);
        } elseif (!$isAdminOrManager && $user->employee) {
            $query->where('employee_id', $user->employee->id);
        } elseif (!$isAdminOrManager && !$user->employee) {
            $query->whereRaw('1 = 0');
        }

        // Appliquer filtre Statut
        $allowedStatuses = [
            LeaveRequest::STATUS_EN_ATTENTE,
            LeaveRequest::STATUS_APPROUVE,
            LeaveRequest::STATUS_REFUSE,
            LeaveRequest::STATUS_ANNULE
        ];
        if ($statusFilter && $statusFilter !== 'all' && in_array($statusFilter, $allowedStatuses)) {
            $query->where('status', $statusFilter);
        } else {
            $statusFilter = null;
        }

        // --- AJOUTER FILTRE PAR DATE ---
        // Filtre sur la date de *début* du congé tombant dans l'intervalle
        if ($dateFromFilter) {
            try {
                $query->where('start_date', '>=', Carbon::parse($dateFromFilter)->startOfDay());
            } catch (\Exception $e) {
                $dateFromFilter = null; // Ignore date invalide
            }
        }
        if ($dateToFilter) {
            try {
                // Inclure les congés qui commencent jusqu'à la fin de la journée
                $query->where('start_date', '<=', Carbon::parse($dateToFilter)->endOfDay());
            } catch (\Exception $e) {
                $dateToFilter = null;
            }
        }
        // --- FIN FILTRE PAR DATE ---

        // Exécuter requête et pagination
        $leaveRequests = $query->paginate(20)->withQueryString(); // Garde TOUS les filtres

        // Récupérer données pour les selects des filtres
        $employees = $isAdminOrManager ? Employee::with('user')->where('status', 'active')->get()->sortBy('user.name') : collect();
        $statuses = LeaveRequest::$statuses;

        // Passer toutes les valeurs de filtre à la vue
        return view('leave_requests.index', compact(
            'leaveRequests', 'employees', 'statusFilter', 'statuses',
            'employeeFilter', 'dateFromFilter', 'dateToFilter' // Ajout des filtres date
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user()->loadMissing('employee');
        $isAdminOrManager = $user->isAdmin(); // || ... adapter

        $employees = null;
        $employee = null;

        if ($isAdminOrManager) {
            $employees = Employee::with('user')->whereHas('user')->where('status', 'active')->get()->sortBy('user.name');
        } elseif ($user->employee) {
            // Récupère l'objet Employee directement
            $employee = Employee::find($user->employee->id);
        } else {
             return Redirect::route('dashboard')->with('error', 'Action non autorisée.');
        }

        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

        // On ne passe plus $balances
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

         // Calcul durée (simple)
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
             } catch (\Exception $e) { Log::error("Erreur stockage justificatif congé: ".$e->getMessage()); return Redirect::back()->withInput()->with('error', 'Erreur stockage justificatif.'); }
         }

         $validatedData['status'] = LeaveRequest::STATUS_EN_ATTENTE;

         // Pas de vérification de solde ici

         try {
            $leaveRequest = LeaveRequest::create($validatedData);
            return Redirect::route('leave-requests.show', $leaveRequest->id)
                ->with('success', 'Votre demande de congé a été soumise avec succès.');
         } catch (\Exception $e) {
             if ($filePath) Storage::disk('public')->delete($filePath);
             Log::error("Erreur création demande congé: " . $e->getMessage());
             return Redirect::back()->withInput()->with('error', 'Erreur soumission demande.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        // $this->authorize('view', $leaveRequest); // TODO
        $leaveRequest->load(['employee.user', 'leaveType', 'approver']);
        return view('leave_requests.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        // $this->authorize('update', $leaveRequest); // TODO
        if ($leaveRequest->status !== 'pending') { return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Non modifiable.'); }

        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();
        $employee = $leaveRequest->employee;
        $employees = null;
        // if (Auth::user()->isAdmin()) { $employees = ...; }

        return view('leave_requests.edit', compact('leaveRequest', 'leaveTypes', 'employee', 'employees')); // Vue edit à créer/adapter
    }

    /**
     * Update the specified resource in storage. (Approbation/Rejet)
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        // $this->authorize('approveReject', $leaveRequest); // TODO

        if ($request->has('action') && $leaveRequest->status === LeaveRequest::STATUS_EN_ATTENTE) {
            $action = $request->input('action');

            if ($action === 'approve') {
                DB::beginTransaction();
                try {
                    $leaveRequest->update([
                        'status' => LeaveRequest::STATUS_APPROUVE,
                        'approver_id' => Auth::id(),
                        'approved_at' => now(),
                        'approver_comment' => $request->input('approver_comment')
                    ]);

                    // Mettre à jour le statut de l'employé en "en congé"
                    $leaveRequest->employee->update([
                        'status' => 'on_leave'
                    ]);

                    DB::commit();
                    return Redirect::route('leave-requests.show', $leaveRequest->id)
                        ->with('success', 'Demande approuvée.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Erreur approbation congé ID {$leaveRequest->id}: " . $e->getMessage());
                    return Redirect::route('leave-requests.show', $leaveRequest->id)
                        ->with('error', 'Erreur lors de l\'approbation.');
                }
            } elseif ($action === 'reject') {
                $request->validate([
                    'approver_comment' => 'required|string|max:500'
                ], [
                    'approver_comment.required' => 'Un commentaire est requis pour rejeter la demande.'
                ]);

                DB::beginTransaction();
                try {
                    $leaveRequest->update([
                        'status' => LeaveRequest::STATUS_REFUSE,
                        'approver_id' => Auth::id(),
                        'approved_at' => now(),
                        'approver_comment' => $request->input('approver_comment')
                    ]);
                    DB::commit();
                    return Redirect::route('leave-requests.show', $leaveRequest->id)
                        ->with('success', 'Demande rejetée.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Erreur rejet congé ID {$leaveRequest->id}: " . $e->getMessage());
                    return Redirect::route('leave-requests.show', $leaveRequest->id)
                        ->with('error', 'Erreur lors du rejet.');
                }
            }
        }

        return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Action non valide ou demande déjà traitée.');
    }


    /**
     * Remove the specified resource from storage. (Annulation)
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
         // $this->authorize('cancel', $leaveRequest); // TODO

         if ($leaveRequest->status === LeaveRequest::STATUS_EN_ATTENTE) {
             DB::beginTransaction(); try {
                 $leaveRequest->status = LeaveRequest::STATUS_ANNULE;
                 $leaveRequest->save(); // Utiliser save() ici car on ne change qu'un champ
                 // Pas de logique de solde à restituer
                 DB::commit();
                 return Redirect::route('leave-requests.index')->with('success', 'Demande annulée.');
             } catch (\Exception $e) { DB::rollBack(); Log::error(...); return Redirect::route('leave-requests.index')->with('error', 'Erreur annulation.'); }
         } else {
              return Redirect::route('leave-requests.index')->with('error', 'Seules les demandes en attente peuvent être annulées.');
         }
    }

    /**
     * Récupère les événements pour FullCalendar.
     */
    public function getLeaveEvents(Request $request): JsonResponse
    {
        Log::debug('getLeaveEvents: Début de la requête.', $request->query());

        try {
            $request->validate([
                'start' => 'sometimes|date',
                'end' => 'sometimes|date|after_or_equal:start',
                'employee_id' => 'sometimes|integer|exists:employees,id'
            ]);

            $start = $request->query('start', Carbon::now()->subYear()->toDateString());
            $end = $request->query('end', Carbon::now()->addYear()->toDateString());
            Log::debug("getLeaveEvents: Période demandée: $start -> $end");

            // --- Récupérer les Demandes de Congé Approuvées ---
            $queryLeave = LeaveRequest::with(['employee.user', 'leaveType']) // Eager load relations
                                     ->where('status', LeaveRequest::STATUS_APPROUVE)
                                     ->where(function($q) use ($start, $end) {
                                         $q->where('start_date', '<=', $end)
                                           ->where('end_date', '>=', $start);
                                     });

            // --- Récupérer les Absences ---
            $queryAbsence = Absence::with(['employee.user']) // Eager load relations
                                 ->where('absence_date', '>=', $start)
                                 ->where('absence_date', '<=', $end);

            // --- Appliquer le filtre Employé ---
            if ($request->filled('employee_id')) {
                 $employeeId = $request->input('employee_id');
                 Log::debug("getLeaveEvents: Filtrage pour employee_id: $employeeId");
                 // !! Ajouter vérification permission !!
                 $queryLeave->where('employee_id', $employeeId);
                 $queryAbsence->where('employee_id', $employeeId);
            } else {
                 Log::debug("getLeaveEvents: Pas de filtre employé appliqué.");
            }

            $leaveRequests = $queryLeave->get();
            $absences = $queryAbsence->get();
            Log::debug("getLeaveEvents: Congés trouvés: " . $leaveRequests->count());
            Log::debug("getLeaveEvents: Absences trouvées: " . $absences->count());

            // --- Formater les Congés ---
            $leaveEvents = $leaveRequests->map(function ($leave) {
                // Vérification détaillée des relations
                $employeeExists = $leave->employee;
                $userExists = $employeeExists && $leave->employee->user;
                $leaveTypeExists = $leave->leaveType;

                if (!$employeeExists || !$userExists || !$leaveTypeExists) {
                    $missing = [];
                    if (!$employeeExists) $missing[] = 'employee';
                    if (!$userExists) $missing[] = 'employee->user';
                    if (!$leaveTypeExists) $missing[] = 'leaveType';
                    Log::warning("getLeaveEvents: Données incomplètes pour LeaveRequest ID {$leave->id}. Manquant: " . implode(', ', $missing));
                    return null; // Sera filtré par ->filter()
                }

                // Formatage si tout est ok
                $employeeName = $leave->employee->user->name;
                $leaveTypeName = $leave->leaveType->name;
                $color = $leave->leaveType->color_code ?? '#3788d8';
                $endDate = Carbon::parse($leave->end_date)->addDay()->toDateString();

                return [
                    'id' => 'leave_'.$leave->id,
                    'title' => $employeeName . ' - ' . $leaveTypeName,
                    'start' => $leave->start_date->format('Y-m-d'),
                    'end' => $endDate,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'url' => route('leave-requests.show', $leave->id), // Lien vers la demande
                    'extendedProps' => ['type' => 'leave_request']
                ];
            })->filter(); // Enlève les éléments null

            // --- Formater les Absences ---
            $absenceEvents = $absences->map(function ($absence) {
                // Vérification détaillée des relations
                $employeeExists = $absence->employee;
                $userExists = $employeeExists && $absence->employee->user;

                if (!$employeeExists || !$userExists) {
                    $missing = [];
                    if (!$employeeExists) $missing[] = 'employee';
                    if (!$userExists) $missing[] = 'employee->user';
                    Log::warning("getLeaveEvents: Données incomplètes pour Absence ID {$absence->id}. Manquant: " . implode(', ', $missing));
                    return null;
                }

                // Formatage si tout est ok
                $employeeName = $absence->employee->user->name;
                $color = $absence->is_justified ? '#fdba74' : '#f87171'; // orange-300 / red-400
                
                // Créer une copie de la date pour éviter de modifier l'objet original
                $startDate = Carbon::parse($absence->absence_date)->format('Y-m-d');
                $endDate = Carbon::parse($absence->absence_date)->addDay()->format('Y-m-d');

                return [
                    'id' => 'absence_'.$absence->id,
                    'title' => $employeeName . ' - Absence (' . ($absence->reason_type ?? 'N/C') .')',
                    'start' => $startDate,
                    'end' => $endDate,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'url' => route('admin.absences.show', $absence->id),
                    'extendedProps' => [
                        'type' => 'absence',
                        'justified' => $absence->is_justified,
                        'reason' => $absence->reason_type,
                        'notes' => $absence->notes
                    ]
                ];
            })->filter();

            // --- Fusionner et Renvoyer ---
            // Au lieu de merge(), on utilise une collection simple
            $allEvents = collect([...$leaveEvents, ...$absenceEvents]);
            Log::debug("getLeaveEvents: Total événements formatés: " . $allEvents->count());

            return response()->json($allEvents);

        } catch (\Exception $e) {
            Log::error("Erreur dans getLeaveEvents: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            // Renvoyer une réponse JSON vide avec un code d'erreur serveur
            return response()->json(['error' => 'Erreur serveur lors de la récupération des événements.'], 500);
        }
    }
     /**
     * Display the leave calendar view.
     */
    public function calendar()
    {
         $employees = Employee::with('user')->where('status', 'active')->get()->sortBy('user.name');
         return view('calendar.index', compact('employees'));
    }
}