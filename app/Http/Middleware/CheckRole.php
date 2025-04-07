<?php

namespace App\Http\Middleware;

use App\Models\User; // Importe ton modèle User
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Vérifie si l'utilisateur authentifié possède l'un des rôles spécifiés.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles Les rôles autorisés (passés depuis la définition de la route).
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Utilise request->user() pour récupérer l'utilisateur authentifié.
        // Le docblock aide certains outils d'analyse statique.
        /** @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null $user */
        $user = $request->user(); // Equivalent à Auth::user() mais souvent mieux typé

        // 1. Vérifier si l'utilisateur est connecté et si c'est une instance de notre modèle User
        //    (Le middleware 'auth' devrait déjà gérer le cas non connecté)
        if (!$user || !$user instanceof User) {
            // Si on arrive ici, c'est une situation anormale ou non gérée
            // (peut-être un autre type d'Authenticatable si tu utilises plusieurs gardes).
            // On refuse l'accès par sécurité.
            abort(403, 'Type d\'utilisateur non valide pour la vérification de rôle.');
        }

        // 2. Vérifier si l'utilisateur possède l'un des rôles requis
        foreach ($roles as $role) {
            // Appelle la méthode `hasRole` que nous avons définie dans App\Models\User
            if ($user->hasRole($role)) {
                // Si l'utilisateur a au moins un des rôles requis, on autorise et on passe à la suite.
                return $next($request);
            }
        }

        // 3. Si la boucle se termine sans trouver de rôle correspondant, l'accès est refusé.
        abort(403, 'ACCÈS NON AUTORISÉ.');
        // Alternative : Redirection vers le tableau de bord avec un message d'erreur
        // return redirect('/dashboard')->with('error', 'Accès non autorisé à cette section.');
    }
}