<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User; // Pour la liste des managers et la mise à jour user
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB; // Pour la transaction dans update
use Illuminate\Support\Facades\Hash; // Si on gérait le mot de passe ici
use Illuminate\Validation\Rule; // Pour les règles unique complexes
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // <<< AJOUTER Request $request
    {
        $search = $request->query('search'); // Récupère le terme de recherche

        // Requête de base avec la relation 'user' chargée
        $query = Employee::with('user');

        // Appliquer le filtre si recherche
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Recherche dans la table 'employees'
                $q->where('employee_number', 'LIKE', "%{$search}%")
                  // Recherche dans la table 'users' liée (via une sous-requête 'whereHas')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Trier et paginer
        // Trier par date d'embauche ou par nom d'utilisateur ?
        $employees = $query->orderBy('hire_date', 'desc')->paginate(20);
        // Ou par nom: $employees = $query->join('users', 'employees.user_id', '=', 'users.id')->orderBy('users.name', 'asc')->select('employees.*')->paginate(20); -> Plus complexe

        // Ajouter la recherche aux liens de pagination
        $employees->appends($request->only(['search']));

        // Passer employés et terme de recherche à la vue (chemin admin ou non ?)
        // Si tes vues sont dans /admin/employees :
        // return view('admin.employees.index', compact('employees', 'search'));
        // Si tes vues sont dans /employees :
        return view('employees.index', compact('employees', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     * Note: La création se fait normalement via la conversion d'un candidat.
     * Cette méthode pourrait être désactivée ou adaptée pour une création manuelle.
     */
    public function create()
{
    // Récupérer les utilisateurs pour la liste des managers potentiels
    $managers = User::orderBy('name')->get(['id', 'name']);
    // On pourrait aussi passer une liste de postes prédéfinis, départements...

    // La vue sera dans admin/employees si on garde cette structure, sinon employees/create
    return view('employees.create', compact('managers')); // Vue à créer
}

    /**
     * Store a newly created resource in storage.
     * (Pas utilisé si la création se fait via OfferController@update)
     */
    public function store(Request $request)
{
    // 1. Valider les données de l'employé ET de l'utilisateur à créer
    $validatedData = $request->validate([
        // Données User
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email', // Email doit être unique
        // Données Employee
        'employee_number' => 'nullable|string|max:255|unique:employees,employee_number',
        'hire_date' => 'required|date',
        'job_title' => 'nullable|string|max:255',
        'department' => 'nullable|string|max:255',
        'manager_id' => 'nullable|exists:users,id',
        'work_location' => 'nullable|string|max:255',
        'social_security_number' => 'nullable|string|max:50|unique:employees,social_security_number',
        'bank_details' => 'nullable|string',
        // Statut initial? Généralement 'active'
        'status' => 'required|in:active,on_leave,terminated', // Permettre de choisir ? Ou forcer 'active' ?
        'termination_date' => 'nullable|required_if:status,terminated|date|after_or_equal:hire_date',
    ]);

    // Gérer le mot de passe : Puisque l'employé ne se connecte pas,
    // on peut mettre une valeur aléatoire non utilisable.
    $randomPassword = Hash::make(Str::random(16)); // Mot de passe haché aléatoire

    // Utiliser une transaction pour créer User et Employee ensemble
    DB::beginTransaction();
    try {
        // 2. Créer l'Utilisateur
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $randomPassword,
            'email_verified_at' => now(), // Marquer comme vérifié
            'role' => 'employee', // Rôle par défaut pour création manuelle
        ]);

        // 3. Créer l'Employé lié à cet utilisateur
        $employee = Employee::create([
            'user_id' => $user->id,
            'candidate_id' => null, // Pas de candidat d'origine ici
            'employee_number' => $validatedData['employee_number'] ?? null,
            'hire_date' => $validatedData['hire_date'],
            'job_title' => $validatedData['job_title'] ?? null,
            'department' => $validatedData['department'] ?? null,
            'manager_id' => $validatedData['manager_id'] ?? null,
            'work_location' => $validatedData['work_location'] ?? null,
            'social_security_number' => $validatedData['social_security_number'] ?? null,
            'bank_details' => $validatedData['bank_details'] ?? null,
            'status' => $validatedData['status'] ?? 'active',
            'termination_date' => ($validatedData['status'] === 'terminated') ? $validatedData['termination_date'] : null,
        ]);

        DB::commit(); // Tout s'est bien passé

        return Redirect::route('employees.index')->with('success', 'Employé créé avec succès.');

    } catch (\Exception $e) {
        DB::rollBack(); // Annuler en cas d'erreur
        Log::error("Erreur création manuelle employé: " . $e->getMessage());
        return Redirect::back()->withInput()->with('error', 'Erreur lors de la création de l\'employé.');
    }
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
             Log::error("Erreur MAJ employé ID {$employee->id}: " . $e->getMessage());
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