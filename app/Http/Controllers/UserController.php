<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Pour vérifier Auth::id()

class UserController extends Controller
{
    // Le middleware est maintenant appliqué par le groupe de routes,
    // donc pas besoin de __construct() ici.

    /** Display a listing of the resource. */
    public function index()
    {
        $users = User::orderBy('name')->paginate(20);
        $roles = ['admin', 'recruiter', 'manager', 'employee']; // Pour filtres/édition
        // Utilise la vue dans un sous-dossier 'admin/users' pour l'organisation
        return view('admin.users.index', compact('users', 'roles'));
    }

    /** Display the specified resource. */
    public function show(User $user)
    {
         // Redirige vers edit
         return redirect()->route('admin.users.edit', $user->id);
    }

    /** Show the form for editing the specified resource. */
    public function edit(User $user)
    {
         $roles = ['admin', 'recruiter', 'manager', 'employee'];
          // Utilise la vue dans un sous-dossier 'admin/users'
         return view('admin.users.edit', compact('user', 'roles'));
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, User $user)
    {
         $validatedUserData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(['admin', 'recruiter', 'manager', 'employee'])], // Mettre à jour cette liste si besoin
            'password' => 'nullable|string|min:8|confirmed',
        ]);

         // Empêcher un admin de changer son propre rôle (sécurité)
         if ($user->id === Auth::id() && $user->role !== $validatedUserData['role']) {
             return Redirect::back()->withInput()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
         }

         try {
             $user->name = $validatedUserData['name'];
             $user->email = $validatedUserData['email'];
             $user->role = $validatedUserData['role'];

             if (!empty($validatedUserData['password'])) {
                 $user->password = Hash::make($validatedUserData['password']);
             }
             $user->save();

             // Utilise le nom de route préfixé
             return Redirect::route('admin.users.index')->with('success', 'Utilisateur mis à jour.');

         } catch (\Exception $e) {
              Log::error("Erreur MAJ utilisateur ID {$user->id}: " . $e->getMessage());
              return Redirect::back()->withInput()->with('error', 'Erreur lors de la mise à jour.');
         }
    }

    /** Remove the specified resource from storage. */
    public function destroy(User $user)
    {
          if ($user->id === Auth::id()) {
               return Redirect::route('admin.users.index')->with('error', 'Impossible de supprimer votre propre compte.');
          }
           if ($user->employee()->exists()) {
                return Redirect::route('admin.users.index')->with('error', 'Utilisateur lié à un employé. Suppression impossible.');
           }
           // Ajouter d'autres vérifications de dépendances ici...

          try {
             $user->delete();
              // Utilise le nom de route préfixé
             return Redirect::route('admin.users.index')->with('success', 'Utilisateur supprimé.');
          } catch (\Exception $e) {
              Log::error("Erreur suppression utilisateur ID {$user->id}: " . $e->getMessage());
              return Redirect::route('admin.users.index')->with('error', 'Erreur de suppression.');
          }
    }
}