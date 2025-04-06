<?php

namespace App\Http\Middleware;

use App\Models\User; // <<< AJOUTE CET IMPORT
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles Les rôles autorisés
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Utilise request->user() qui est souvent mieux typé
        /** @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null $user */
        $user = $request->user(); // Ou Auth::user();

        // Vérifier si l'utilisateur est connecté ET est bien une instance de notre modèle User
        if (!$user || !$user instanceof User) {
             // Si pas connecté, le middleware 'auth' devrait déjà avoir redirigé
             // Si ce n'est pas le bon type d'objet (improbable), refuser l'accès
             abort(403, 'Type d\'utilisateur non valide pour la vérification de rôle.');
             // Ou rediriger: return redirect()->route('login');
        }

        // Maintenant, l'éditeur devrait savoir que $user est un App\Models\User
        foreach ($roles as $role) {
            if ($user->hasRole($role)) { // Le soulignement devrait disparaître ici
                return $next($request);
            }
        }

        abort(403, 'ACCÈS NON AUTORISÉ.');
    }
}