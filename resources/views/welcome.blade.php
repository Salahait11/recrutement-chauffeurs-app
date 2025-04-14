<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Recrutement Chauffeurs App - Nouveautés</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex p-6 lg:p-8 items-center justify-center min-h-screen flex-col">
        <header class="w-full max-w-4xl mx-auto text-sm mb-6">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600"
                        >
                            Tableau de Bord
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
                        >
                            Connexion
                        </a>
                    @endauth
                </nav>
            @endif
        </header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-4xl w-full flex-col items-center text-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                 <svg class="w-20 h-20 text-indigo-600 dark:text-indigo-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0c1.657 0 3-.895 3-2s-1.343-2-3-2-3-.895-3-2 1.343-2 3-2m-3 4h6m2 0h2M4 12h2m-2 0l1.17-1.83A4.002 4.002 0 018.999 8h6.002a4 4 0 013.83 2.17L20 12M4 12v6a2 2 0 002 2h12a2 2 0 002-2v-6m-4 0h-4"></path></svg>

                <h1 class="text-3xl font-semibold mb-2 dark:text-white">Bienvenue sur l'Application de Recrutement Chauffeurs</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Gérez efficacement le processus de recrutement de vos futurs chauffeurs. Découvrez nos dernières améliorations !
                </p>

                <div class="text-left w-full md:w-3/4 lg:w-2/3 my-6 space-y-4">
                    <h2 class="text-2xl font-semibold mb-4 dark:text-white text-center">Nouvelles Fonctionnalités et Améliorations</h2>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
                        <li>
                            <strong class="font-semibold">Assistance au codage IA :</strong> Gemini dans IDX est maintenant unifié avec Gemini dans Firebase et amélioré pour faciliter la sélection parmi différents modèles Gemini adaptés à vos tâches de codage.
                        </li>
                        <li>
                            <strong class="font-semibold">Prompting multimodal :</strong> Inclut le langage naturel, les images et les outils de dessin, y compris l'agent App Prototyping pour générer des applications full-stack (initialement des applications Next.js).
                        </li>
                        <li>
                            <strong class="font-semibold">Intégration Firebase améliorée :</strong> Intégration plus profonde avec les services Firebase, comme la publication d'applications web sur Firebase App Hosting et la création de flux IA ou de Génération Augmentée par Récupération (RAG) avec Genkit.
                        </li>
                         <li>
                            <strong class="font-semibold">Performances améliorées :</strong> Temps de chargement et de construction plus rapides et performances globales améliorées.
                        </li>
                         <li>
                            <strong class="font-semibold">Plus d'options de personnalisation :</strong> Plus de façons de personnaliser votre environnement de développement selon vos besoins.
                        </li>
                    </ul>
                </div>

                @auth
                     <a href="{{ url('/dashboard') }}" class="px-6 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 mt-4">
                        Accéder au Tableau de Bord
                    </a>
                @else
                     <a href="{{ route('login') }}" class="px-6 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 mt-4">
                        Se Connecter
                    </a>
                @endauth
            </main>
        </div>

        <footer class="mt-8 text-sm text-gray-500 dark:text-gray-400">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </footer>
    </body>
</html>
