<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee; // Pour lier la demande à l'employé connecté
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // Pour le justificatif dans store/destroy?
use Illuminate\Validation\Rules\File; // Pour la validation fichier dans store
use Carbon\Carbon; // Pour calculer la durée dans store
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * Affiche les demandes de l'employé connecté ou toutes les demandes pour admin/manager.
     */
    public function index()
    {
        $user = Auth::user();
        // S'assurer que l'utilisateur et sa relation employee sont chargés si nécessaire
        $user->loadMissing('employee');

        $query = LeaveRequest::with(['employee.user', 'leaveType', 'approver'])
                             ->orderBy('start_date', 'desc'); // Ordre logique

        // !! Remplacer cette logique par une vraie gestion des rôles/permissions !!
        // Exemple simple: si l'user n'est PAS admin ET n'a PAS d'employés managés, il ne voit que les siens
        $isManagerOrAdmin = false; // A déterminer avec les rôles réels
        // if ($user->hasRole('admin') || $user->hasRole('rh_manager') || $user->managedEmployees()->exists()) {
        //    $isManagerOrAdmin = true;
        // }

        if (!$isManagerOrAdmin) {
           if($user->employee) {
               $query->where('employee_id', $user->employee->id);
           } else {
               // L'utilisateur n'est pas un employé et pas un manager/admin, ne rien montrer
               $query->whereRaw('1 = 0'); // Astuce pour retourner une collection vide
           }
        } else {
            // Si manager, pourrait filtrer sur ses équipes ? Pour l'instant, on montre tout si manager/admin
            // $managedEmployeeIds = $user->managedEmployees()->pluck('id');
            // $query->whereIn('employee_id', $managedEmployeeIds);
        }

        $leaveRequests = $query->paginate(20);

        return view('leave_requests.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $employee = $user->employee; // Récupère le profil employé de l'utilisateur connecté

        if (!$employee) {
            return Redirect::route('dashboard')->with('error', 'Vous devez être enregistré comme employé pour demander un congé.');
        }

        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

        // Calcul des soldes (non implémenté)
        // $balances = ... ;

        return view('leave_requests.create', compact('employee', 'leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        if (!$employee) { return Redirect::route('dashboard')->with('error', 'Profil employé non trouvé.'); }

         // 1. Valider les données
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
            'attachment' => [
                'nullable',
                File::types(['pdf', 'jpg', 'png', 'jpeg'])->max(2 * 1024)
            ],
        ], [
             'leave_type_id.required' => 'Le type de congé est obligatoire.',
             'start_date.required' => 'La date de début est obligatoire.',
             'start_date.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
             'end_date.required' => 'La date de fin est obligatoire.',
             'end_date.after_or_equal' => 'La date de fin doit être après ou égale à la date de début.',
             'attachment.types' => 'Le justificatif doit être un PDF, JPG ou PNG.',
             'attachment.max' => 'Le justificatif ne doit pas dépasser 2 Mo.',
        ]);

         // Sécurité: Vérifier l'employee_id
         if ((int)$validatedData['employee_id'] !== $employee->id) {
             abort(403, 'Action non autorisée.');
         }

         // Calcul durée (simple)
         try {
            $start = Carbon::parse($validatedData['start_date']);
            $end = Carbon::parse($validatedData['end_date']);
            // Logique de calcul à affiner (jours ouvrés, demi-journées...)
             $validatedData['duration_days'] = $start->diffInDays($end) + 1;
        } catch (\Exception $e) {
             return Redirect::back()->withInput()->with('error', 'Format de date invalide.');
        }

         // Gérer l'upload du justificatif
         if ($request->hasFile('attachment')) {
             try {
                $file = $request->file('attachment');
                $filePath = $file->store('leave_attachments/' . $employee->id, 'public');
                $validatedData['attachment_path'] = $filePath;
             } catch (\Exception $e) {
                 \Log::error("Erreur stockage justificatif: " . $e->getMessage());
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
             if (isset($validatedData['attachment_path']) && isset($filePath)) {
                 Storage::disk('public')->delete($filePath);
             }
             \Log::error("Erreur création demande congé: " . $e->getMessage());
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
     // !! Logique d'autorisation à implémenter !!
     // $this->authorize('view', $leaveRequest);

     $leaveRequest->load(['employee.user', 'leaveType', 'approver']); // Charger relations
     return view('leave_requests.show', compact('leaveRequest'));
}

    /**
     * Show the form for editing the specified resource.
     * (Peut-être seulement pour annuler via un bouton sur show/index)
     */
    public function edit(LeaveRequest $leaveRequest)
    {
         // !! Implémenter la logique d'autorisation !!
         // $this->authorize('update', $leaveRequest);

         if ($leaveRequest->status !== 'pending') {
             return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Seules les demandes en attente peuvent être modifiées/annulées.');
         }
         $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();
         return view('leave_requests.edit', compact('leaveRequest', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     * (Principalement pour l'approbation/rejet par Manager/RH)
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
{
    // !! Implémenter Autorisation !!
    // $this->authorize('approve', $leaveRequest); // Ou une permission spécifique

    if ($request->has('action')) {
        $action = $request->input('action');

        // --- APPROBATION ---
        if ($action === 'approve' && $leaveRequest->status === 'pending') {
            // Pas besoin de valider le commentaire ici car il est optionnel pour l'approbation
            DB::beginTransaction(); // Utiliser transaction
            try {
                $leaveRequest->status = 'approved';
                $leaveRequest->approver_id = Auth::id();
                $leaveRequest->approved_at = now();
                $leaveRequest->approver_comment = $request->input('approver_comment');
                $leaveRequest->save();

                // !! Logique de déduction du solde ici !!

                DB::commit();
                // Envoyer notification ?
                return Redirect::route('leave-requests.show', $leaveRequest->id)->with('success', 'Demande approuvée.');
            } catch (\Exception $e) {
                 DB::rollBack();
                 \Log::error("Erreur approbation congé ID {$leaveRequest->id}: " . $e->getMessage());
                 return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Erreur lors de l\'approbation.');
            }
        }
        // --- REJET ---
        elseif ($action === 'reject' && $leaveRequest->status === 'pending') {
            // Valider que le commentaire est requis pour le rejet
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
                 // Envoyer notification ?
                return Redirect::route('leave-requests.show', $leaveRequest->id)->with('success', 'Demande rejetée.');
            } catch (\Exception $e) {
                 DB::rollBack();
                 \Log::error("Erreur rejet congé ID {$leaveRequest->id}: " . $e->getMessage());
                 return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Erreur lors du rejet.');
            }
        } else {
            return Redirect::route('leave-requests.show', $leaveRequest->id)->with('error', 'Action non valide ou demande déjà traitée.');
        }
    }

    // Gérer la modification standard par l'employé (si nécessaire/autorisé)
    // ...

    return Redirect::route('leave-requests.index')->with('info', 'Action non reconnue.');
}

    /**
     * Remove the specified resource from storage. (Utilisé pour Annuler)
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
         // !! Implémenter la logique d'autorisation (employé ou admin/RH?) !!
         // $this->authorize('delete', $leaveRequest); // Ou 'cancel' ?

         if ($leaveRequest->status === 'pending') {
             try {
                 $leaveRequest->status = 'canceled';
                 // Qui annule ?
                 // $leaveRequest->approver_id = Auth::id(); // Ou un champ 'canceled_by_id' ?
                 // $leaveRequest->approved_at = now(); // Ou 'canceled_at' ?
                 $leaveRequest->save();

                 // Renvoyer le crédit de jours si affecte le solde et si déjà déduit ? (logique complexe)

                 return Redirect::route('leave-requests.index')->with('success', 'Demande de congé annulée.');

             } catch (\Exception $e) {
                  \Log::error("Erreur annulation demande congé ID {$leaveRequest->id}: " . $e->getMessage());
                  return Redirect::route('leave-requests.index')->with('error', 'Erreur lors de l\'annulation.');
             }
         } else {
              return Redirect::route('leave-requests.index')->with('error', 'Seules les demandes en attente peuvent être annulées.');
         }
    }

    // Méthodes spécifiques pour approbation/rejet si on utilise des routes dédiées
    /*
    public function approve(Request $request, LeaveRequest $leaveRequest) { ... }
    public function reject(Request $request, LeaveRequest $leaveRequest) { ... }
    */
}