<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    // Le middleware est appliqué par le groupe de routes dans web.php

    /** Display a listing of users. */
    public function index()
    {
        $users = User::orderBy('name')->paginate(20);
        $roles = ['admin', 'recruiter', 'manager', 'employee'];
        // Utilise la vue dans le sous-dossier 'admin/users'
        return view('admin.users.index', compact('users', 'roles'));
    }

    /** Show the form for creating a new user */
    public function create()
    {
         $roles = ['admin', 'employee']; // Rôles que l'admin peut créer
         // Utilise la vue dans le sous-dossier 'admin/users'
         return view('admin.users.create', compact('roles'));
    }

    /** Store a newly created user */
    public function store(Request $request)
    {
         $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', Rule::in(['admin', 'employee'])], // Rôles permis
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

         try {
             User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'role' => $validatedData['role'],
                'password' => Hash::make($validatedData['password']),
                'email_verified_at' => now(), // Marquer vérifié si créé par admin
            ]);

            // Utilise le nom de route préfixé
             return Redirect::route('admin.users.index')->with('success', 'Nouvel utilisateur créé.');

         } catch (\Exception $e) {
              Log::error("Erreur création utilisateur par admin: " . $e->getMessage());
              return Redirect::back()->withInput()->with('error', 'Erreur création utilisateur.');
         }
    }

    /** Display the specified resource (redirects to edit). */
    public function show(User $user)
    {
         // Utilise le nom de route préfixé
         return redirect()->route('admin.users.edit', $user->id);
    }

    /** Show the form for editing the specified resource. */
    public function edit(User $user)
    {
         $roles = ['admin', 'recruiter', 'manager', 'employee']; // Tous les rôles possibles
         // Utilise la vue dans le sous-dossier 'admin/users'
         return view('admin.users.edit', compact('user', 'roles'));
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, User $user)
    {
         $validatedUserData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(['admin', 'recruiter', 'manager', 'employee'])],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

         // Empêcher changement de son propre rôle
         if ($user->id === Auth::id() && $user->role !== $validatedUserData['role']) {
             unset($validatedUserData['role']);
             Log::warning("Tentative par l'admin ID ".Auth::id()." de changer son propre rôle.");
             // Optionnel: ajouter un message flash d'avertissement en plus du succès ?
             // session()->flash('warning', 'Votre propre rôle n\'a pas été modifié.');
         }

         try {
             $updateData = [
                 'name' => $validatedUserData['name'],
                 'email' => $validatedUserData['email'],
             ];
             // Appliquer le rôle seulement s'il est dans les données validées (non supprimé par la condition ci-dessus)
             if (isset($validatedUserData['role'])) {
                 $updateData['role'] = $validatedUserData['role'];
             }
             // Mettre à jour mot de passe si fourni
             if (!empty($validatedUserData['password'])) {
                 $updateData['password'] = Hash::make($validatedUserData['password']);
             }

             $user->update($updateData); // Utilise update() pour mass assignment

             // Utilise le nom de route préfixé
             return Redirect::route('admin.users.index')->with('success', 'Utilisateur mis à jour.');

         } catch (\Exception $e) {
              Log::error("Erreur MAJ utilisateur ID {$user->id}: " . $e->getMessage());
              return Redirect::back()->withInput()->with('error', 'Erreur mise à jour.');
         }
    }

    /** Remove the specified resource from storage. */
    public function destroy(User $user)
    {
          if ($user->id === Auth::id()) {
               return Redirect::route('admin.users.index')->with('error', 'Suppression de votre compte impossible.');
          }
           if ($user->employee()->exists()) {
                return Redirect::route('admin.users.index')->with('error', 'Utilisateur lié à un employé. Suppression impossible.');
           }
           // Ajouter d'autres vérifications...

          try {
             $user->delete();
             // Utilise le nom de route préfixé
             return Redirect::route('admin.users.index')->with('success', 'Utilisateur supprimé.');
          } catch (QueryException $e) {
              Log::error("Erreur suppression utilisateur FK ID {$user->id}: " . $e->getMessage());
              return Redirect::route('admin.users.index')->with('error', 'Impossible de supprimer : utilisateur référencé ailleurs.');
          } catch (\Exception $e) {
              Log::error("Erreur suppression utilisateur ID {$user->id}: " . $e->getMessage());
              return Redirect::route('admin.users.index')->with('error', 'Erreur suppression.');
          }
    }
}