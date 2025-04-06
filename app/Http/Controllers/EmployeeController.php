<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User; // Pour la liste des managers et la mise à jour user
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB; // Pour la transaction dans update
use Illuminate\Support\Facades\Hash; // Si on gérait le mot de passe ici
use Illuminate\Validation\Rule; // Pour les règles unique complexes

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupère les employés avec leur information User associée
        $employees = Employee::with('user')
                             ->orderBy('hire_date', 'desc') // Trier par date d'embauche
                             ->paginate(20);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     * Note: La création se fait normalement via la conversion d'un candidat.
     * Cette méthode pourrait être désactivée ou adaptée pour une création manuelle.
     */
    public function create()
    {
        // Exemple si on voulait une création manuelle :
        // $usersWithoutEmployeeProfile = User::whereDoesntHave('employee')->get();
        // return view('employees.create', compact('usersWithoutEmployeeProfile'));
        abort(403, 'La création d\'employé se fait via l\'acceptation d\'une offre candidat.');
    }

    /**
     * Store a newly created resource in storage.
     * (Pas utilisé si la création se fait via OfferController@update)
     */
    public function store(Request $request)
    {
        abort(403, 'Méthode non applicable pour la création d\'employé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load(['user', 'candidate', 'manager']); // Charger relations utiles
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $employee->load('user'); // Charger la relation user pour pré-remplir nom/email

        // Récupérer tous les utilisateurs pour la liste des managers potentiels
        // Exclure l'employé lui-même de la liste
        $managers = User::where('id', '!=', $employee->user_id)
                        ->orderBy('name')
                        ->get(['id', 'name']);

        return view('employees.edit', compact('employee', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
         // 1. Valider les données de l'employé
        $validatedEmployeeData = $request->validate([
            // Utilisation de Rule::unique pour ignorer l'employé actuel
            'employee_number' => [
                'nullable', 'string', 'max:255',
                Rule::unique('employees', 'employee_number')->ignore($employee->id),
            ],
            'hire_date' => 'required|date',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'manager_id' => 'nullable|exists:users,id', // Doit être un ID valide dans la table users
            'work_location' => 'nullable|string|max:255',
            'social_security_number' => [
                'nullable', 'string', 'max:50',
                 Rule::unique('employees', 'social_security_number')->ignore($employee->id),
             ],
            'bank_details' => 'nullable|string',
            'status' => 'required|in:active,on_leave,terminated',
            // Valider termination_date seulement si status est 'terminated'
            // et doit être après ou égale à la date d'embauche
            'termination_date' => 'nullable|required_if:status,terminated|date|after_or_equal:hire_date',
        ], [
            // Messages customisés
            'manager_id.exists' => 'Le manager sélectionné n\'est pas valide.',
            'termination_date.required_if' => 'La date de fin est requise si le statut est Terminé.',
            'termination_date.after_or_equal' => 'La date de fin doit être après ou égale à la date d\'embauche.'
        ]);

        // 1b. Valider les données de l'utilisateur lié (nom, email)
        // Charger l'utilisateur pour obtenir son ID pour la règle unique
         $user = $employee->user;
         if(!$user) {
             // Ne devrait pas arriver si la DB est cohérente
             return Redirect::back()->withInput()->with('error', 'Utilisateur associé non trouvé.');
         }

         $validatedUserData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id), // Ignorer l'utilisateur actuel
             ],
             // 'password' => 'nullable|string|min:8|confirmed', // Si on gérait le mdp ici
        ]);

         // S'assurer que termination_date est null si status n'est pas 'terminated'
         if ($validatedEmployeeData['status'] !== 'terminated') {
             $validatedEmployeeData['termination_date'] = null;
         }

         // Utiliser une transaction car on modifie deux tables (employees et users)
         DB::beginTransaction();
         try {
             // 2. Mettre à jour l'employé
             $employee->update($validatedEmployeeData);

             // 3. Mettre à jour l'utilisateur lié
             $user->name = $validatedUserData['name'];
             $user->email = $validatedUserData['email'];
             // if (!empty($validatedUserData['password'])) { $user->password = Hash::make($validatedUserData['password']); }
             $user->save();

             DB::commit(); // Confirmer les changements

             return Redirect::route('employees.show', $employee->id)->with('success', 'Informations employé mises à jour avec succès !');

        } catch (\Exception $e) {
             DB::rollBack(); // Annuler en cas d'erreur
             \Log::error("Erreur MAJ employé ID {$employee->id}: " . $e->getMessage());
             return Redirect::back()->withInput()->with('error', 'Erreur lors de la mise à jour des informations.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * La suppression d'un employé est sensible. Doit-on le supprimer
     * ou juste le marquer comme 'terminated' ?
     * Pour l'instant, on désactive la suppression physique.
     */
    public function destroy(Employee $employee)
    {
         // Option 1: Marquer comme terminé au lieu de supprimer
         // $employee->status = 'terminated';
         // $employee->termination_date = now();
         // $employee->save();
         // return Redirect::route('employees.index')->with('success', 'Employé marqué comme terminé.');

        // Option 2: Désactiver complètement la suppression pour l'instant
         abort(403, 'La suppression physique des employés n\'est pas autorisée.');

         // Option 3: Suppression réelle (si autorisée et gérée avec précautions)
         /*
         try {
             // Supprimer l'utilisateur associé ? Ou juste l'employé ?
             // Si on supprime l'user, l'employé sera supprimé par cascade (onDelete('cascade'))
             // $employee->user->delete();

             // Ou juste supprimer l'employé (laissera l'utilisateur orphelin si pas géré)
             $employee->delete();
             return Redirect::route('employees.index')->with('success', 'Employé supprimé.');
         } catch (\Exception $e) {
             \Log::error("Erreur suppression employé ID {$employee->id}: " . $e->getMessage());
             return Redirect::route('employees.index')->with('error', 'Erreur suppression.');
         }
         */
    }
}