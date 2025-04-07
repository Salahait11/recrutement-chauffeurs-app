<?php

namespace App\Http\Controllers;

// Core Models
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\User;

// Laravel Facades & Classes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Pour transactions approbation/rejet
use Illuminate\Validation\Rules\File;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * Adapte l'affichage selon le rôle.
     */
     // Dans LeaveRequestController@index
 public function index()
 {
     // Plus besoin de vérifier le rôle ici si la route est protégée
     $query = LeaveRequest::with(['employee.user', 'leaveType', 'approver'])
                          ->orderBy('start_date', 'desc');

     $leaveRequests = $query->paginate(20);

     return view('leave_requests.index', compact('leaveRequests'));
 }
    /**
     * Show the form for creating a new resource.
     * Permet à l'admin/manager de choisir l'employé.
     */
     // Dans LeaveRequestController@create
 public function create()
 {
     // L'admin voit toujours la liste complète des employés actifs
     $employees = Employee::with('user')
                         ->whereHas('user')
                         ->where('status', 'active')
                         ->get()
                         ->sortBy('user.name');

     $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

     // On ne passe plus $employee, seulement $employees
     return view('leave_requests.create', compact('employees', 'leaveTypes'));
 }
    /**
     * Store a newly created resource in storage.
     * Gère la création par un employé ou par un admin pour un employé.
     */
    public function store(Request $request)
    {
         // Valider les données (employee_id est envoyé par le formulaire)
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
            'attachment' => ['nullable', File::types(['pdf', 'jpg', 'png', 'jpeg'])->max(2 * 1024) ],
        ], [
             'employee_id.required' => 'Veuillez sélectionner l\'employé concerné.',
             'leave_type_id.required' => 'Le type de congé est obligatoire.',
             'start_date.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
             'end_date.after_or_equal' => 'La date de fin doit être après ou égale à la date de début.',
             'attachment.types' => 'Le justificatif doit être un PDF, JPG ou PNG.',
             'attachment.max' => 'Le justificatif ne doit pas dépasser 2 Mo.',
        ]);

         // Calcul durée (simple)
         try {
            $start = Carbon::parse($validatedData['start_date']);
            $end = Carbon::parse($validatedData['end_date']);
            $validatedData['duration_days'] = $start->diffInDaysFiltered(function(Carbon $date) {
                 // Exclure weekends (exemple simple, ne gère pas les fériés)
                 return !$date->isWeekend();
            }, $end);
             // Ajouter logique pour demi-journées si nécessaire
             if ($start->format('H:i') != '00:00' || $end->format('H:i') != '00:00') {
                 // Logique plus complexe pour demi-journées... ou on ignore pour l'instant.
                 // Pour simplifier, on pourrait arrondir ou utiliser un calcul basé sur les heures.
                 // $validatedData['duration_days'] = round($start->diffInHours($end) / 8, 2); // Exemple basé sur 8h/jour
                 // Pour l'instant, restons sur diffInDays + 1 pour inclure le dernier jour complet.
                 $validatedData['duration_days'] = $start->diffInDays($end) + 1; // Revert à la version simple
             }

         } catch (\Exception $e) {
             return Redirect::back()->withInput()->with('error', 'Format de date invalide.');
         }


         // Gérer l'upload du justificatif
         $filePath = null; // Initialiser
         if ($request->hasFile('attachment')) {
             try {
                $file = $request->file('attachment');
                // Utilise l'employee_id validé pour le chemin
                $filePath = $file->store('leave_attachments/' . $validatedData['employee_id'], 'public');
                $validatedData['attachment_path'] = $filePath;
             } catch (\Exception $e) {
                 Log::error("Erreur stockage justificatif congé: " . $e->getMessage());
                 return Redirect::back()->withInput()->with('error', 'Erreur lors du stockage du justificatif.');
             }
         }

         // Statut initial
         $validatedData['status'] = 'pending';

         // Vérifier le solde (non implémenté)

         // Créer la demande
         try {
            LeaveRequest::create($validatedData);
         } catch (\Exception $e) {
             // Supprimer le fichier si l'enregistrement DB échoue
             if (isset($validatedData['attachment_path']) && $filePath) {
                 Storage::disk('public')->delete($filePath);
             }
             Log::error("Erreur création demande congé: " . $e->getMessage());
             return Redirect::back()->withInput()->with('error', 'Erreur lors de la soumission de la demande.');
        }

        // Envoyer notification (non implémenté)

        return Redirect::route('leave-requests.index')->with('success', 'Demande de congé soumise avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
         // !! Implémenter la logique d'autorisation (Policy) !!
         // $this->authorize('view', $leaveRequest);

         $leaveRequest->load(['employee.user', 'leaveType', 'approver']);
         return view('leave_requests.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     * (Probablement non utilisé, on annule via destroy)
     */
    public function edit(LeaveRequest $leaveRequest)
    {
         // !! Implémenter la logique d'autorisation !!
         // $this->authorize('update', $leaveRequest);

         if ($leaveRequest->status !== 'pending') {
             return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Seules les demandes en attente peuvent être modifiées/annulées.');
         }
         $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();
         // Passe l'employé pour réutiliser la même vue que create ?
         $employee = $leaveRequest->employee;
         return view('leave_requests.edit', compact('leaveRequest', 'leaveTypes', 'employee')); // Vue edit à créer si besoin
         // Ou simplement rediriger vers index/show car l'édition est limitée
         // abort(403, 'Modification non permise, veuillez annuler et recréer.');
    }

    /**
     * Update the specified resource in storage.
     * Gère l'approbation/rejet par Manager/RH.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        // !! Implémenter la logique d'autorisation pour approuver/rejeter !!
        // $this->authorize('approveReject', $leaveRequest); // Permission spécifique ?

        if ($request->has('action') && $leaveRequest->status === 'pending') {
            $action = $request->input('action');

            if ($action === 'approve') {
                 // Pas de validation spécifique pour le commentaire d'approbation (optionnel)
                DB::beginTransaction();
                try {
                    $leaveRequest->status = 'approved';
                    $leaveRequest->approver_id = Auth::id();
                    $leaveRequest->approved_at = now();
                    $leaveRequest->approver_comment = $request->input('approver_comment');
                    $leaveRequest->save();
                    // !! Logique de déduction du solde ici !!
                    DB::commit();
                    return Redirect::route('leave-requests.show', $leaveRequest->id)->with('success', 'Demande approuvée.');
                } catch (\Exception $e) {
                     DB::rollBack();
                     Log::error("Erreur approbation congé ID {$leaveRequest->id}: " . $e->getMessage());
                     return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Erreur lors de l\'approbation.');
                }

            } elseif ($action === 'reject') {
                 $request->validate(
                    ['approver_comment' => 'required|string|max:500'],
                    ['approver_comment.required' => 'Un commentaire est requis pour rejeter la demande.']
                 );
                 DB::beginTransaction();
                 try {
                    $leaveRequest->status = 'rejected';
                    $leaveRequest->approver_id = Auth::id();
                    $leaveRequest->approved_at = now(); // Date du rejet
                    $leaveRequest->approver_comment = $request->input('approver_comment');
                    $leaveRequest->save();
                    DB::commit();
                    return Redirect::route('leave-requests.show', $leaveRequest->id)->with('success', 'Demande rejetée.');
                } catch (\Exception $e) {
                     DB::rollBack();
                     Log::error("Erreur rejet congé ID {$leaveRequest->id}: " . $e->getMessage());
                     return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Erreur lors du rejet.');
                }
            } else {
                 return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Action non valide.');
            }
        } else if ($leaveRequest->status !== 'pending') {
             return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Cette demande a déjà été traitée.');
        }

        // Si ce n'est pas une action approve/reject, peut-être une modif par l'employé ?
        // Logique de modification standard (si l'employé modifie sa propre demande 'pending')
        // ... (validation similaire à store, puis $leaveRequest->update(...)) ...

        return Redirect::route('leave-requests.index')->with('info', 'Aucune action effectuée.');
    }

    /**
     * Remove the specified resource from storage. (Utilisé pour Annuler par l'employé)
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
         // !! Implémenter la logique d'autorisation (employé concerné ou admin/RH) !!
         // $this->authorize('cancel', $leaveRequest);

         if ($leaveRequest->status === 'pending') {
             try {
                 $leaveRequest->status = 'canceled';
                 $leaveRequest->save();
                 // Renvoyer le crédit ? (non implémenté)
                 return Redirect::route('leave-requests.index')->with('success', 'Demande de congé annulée.');
             } catch (\Exception $e) {
                  Log::error("Erreur annulation demande congé ID {$leaveRequest->id}: " . $e->getMessage());
                  return Redirect::route('leave-requests.index')->with('error', 'Erreur lors de l\'annulation.');
             }
         } else {
              return Redirect::route('leave-requests.index')->with('error', 'Seules les demandes en attente peuvent être annulées.');
         }
    }

    /**
     * Récupère les demandes de congé approuvées pour FullCalendar.
     */
    public function getLeaveEvents(Request $request): JsonResponse
    {
        $request->validate([
            'start' => 'sometimes|date',
            'end' => 'sometimes|date|after_or_equal:start',
            'employee_id' => 'sometimes|integer|exists:employees,id' // Ajout validation pour filtre optionnel
        ]);

        // Dates demandées par FullCalendar
        $start = $request->query('start', Carbon::now()->subYear()->toDateString());
        $end = $request->query('end', Carbon::now()->addYear()->toDateString());

        // Requête de base: toutes les demandes approuvées dans l'intervalle
        $query = LeaveRequest::with(['employee.user', 'leaveType'])
                             ->where('status', 'approved')
                             ->where(function($q) use ($start, $end) {
                                 // Condition de chevauchement plus robuste
                                 $q->where('start_date', '<=', $end)
                                   ->where('end_date', '>=', $start);
                             });

        // --- FILTRE OPTIONNEL (Pourrait être utilisé pour un calendrier personnel) ---
        // Si un employee_id est passé en paramètre d'URL (?employee_id=X), on filtre
        if ($request->has('employee_id')) {
            // !! Ajouter une vérification ici : est-ce que l'utilisateur connecté
            //    a le droit de voir les congés de cet employé spécifique ?
            //    (soit c'est lui-même, soit c'est son manager/admin)
            //    Exemple simple : if (Auth::id() == $the_employee->user_id || Auth::user()->isAdmin()) ...
             $query->where('employee_id', $request->input('employee_id'));
        }
        // --- FIN FILTRE OPTIONNEL ---
        // Comme on n'ajoute pas le filtre par défaut, l'admin verra tout.

        $leaveRequests = $query->get();

        // Formatage pour FullCalendar (INCHANGÉ)
        $events = $leaveRequests->map(function ($leave) {
             $employeeName = $leave->employee && $leave->employee->user ? $leave->employee->user->name : 'Employé Supprimé';
             $leaveTypeName = $leave->leaveType ? $leave->leaveType->name : 'Type Supprimé';
             $color = $leave->leaveType ? $leave->leaveType->color_code : '#808080';
             $endDate = Carbon::parse($leave->end_date)->addDay()->toDateString();

            return [
                'id' => $leave->id,
                'title' => $employeeName . ' - ' . $leaveTypeName,
                'start' => $leave->start_date->format('Y-m-d'),
                'end' => $endDate,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'url' => route('leave-requests.show', $leave->id),
                'extendedProps' => [ /* ... */ ]
            ];
        });

        return response()->json($events);
    }

     /**
     * Display the leave calendar.
     */
    public function calendar()
    {
        // La vue n'a pas besoin de données spécifiques pour l'instant
        return view('calendar.index');
    }
}