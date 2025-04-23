<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Recrutement Chauffeurs</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
                /* Styles Tailwind ici... */
                /* (Conserver les mêmes styles que dans le fichier original) */
            </style>
        @endif
    </head>
    <body class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-blue-900 text-gray-900 dark:text-gray-100 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-truck text-blue-600 dark:text-blue-400 text-2xl"></i>
                        <span class="font-semibold text-lg text-blue-800 dark:text-blue-300">Recrutement Chauffeurs</span>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 shadow-md"
                            >
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Tableau de bord
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 shadow-md"
                            >
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Connexion
                            </a>
                        @endauth
                    </div>
                </nav>
            @endif
        </header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-gray-800 shadow-xl rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    <h1 class="text-3xl font-bold mb-4 text-blue-700 dark:text-blue-400">Recrutement de chauffeurs professionnels</h1>
                    <p class="mb-6 text-gray-600 dark:text-gray-300 text-lg">
                        Rejoignez notre équipe de conducteurs expérimentés et bénéficiez d'avantages compétitifs.
                    </p>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-4 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-200 border border-blue-100 dark:border-blue-800">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-blue-800 dark:text-blue-300">Salaire compétitif</h3>
                                <p class="text-gray-600 dark:text-gray-300">Avantages sociaux et primes attractives</p>
                            </div>
                        </li>
                        <li class="flex items-center gap-4 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-200 border border-blue-100 dark:border-blue-800">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-blue-800 dark:text-blue-300">Horaires flexibles</h3>
                                <p class="text-gray-600 dark:text-gray-300">Planification optimisée selon vos besoins</p>
                            </div>
                        </li>
                        <li class="flex items-center gap-4 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-200 border border-blue-100 dark:border-blue-800">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center">
                                <i class="fas fa-car text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-blue-800 dark:text-blue-300">Véhicules modernes</h3>
                                <p class="text-gray-600 dark:text-gray-300">Flotte récente et bien entretenue</p>
                            </div>
                        </li>
                    </ul>
                    
                   
                </div>
                
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 dark:from-blue-800 dark:to-indigo-900 relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden shadow-xl">
                    <div class="absolute inset-0 bg-black opacity-10"></div>
                    <div class="relative w-full h-full flex items-center justify-center p-8">
                        <div class="text-center">
                            <i class="fas fa-truck-moving text-white text-6xl mb-6"></i>
                            <h2 class="text-3xl font-bold text-white mb-4">Rejoignez notre équipe</h2>
                            <p class="text-white text-lg mb-8">
                                Devenez chauffeur professionnel et faites partie d'une équipe dynamique
                            </p>
                            <div class="grid grid-cols-2 gap-4 text-white">
                                <div class="bg-white bg-opacity-10 rounded-lg p-4 backdrop-blur-sm border border-white/20">
                                    <i class="fas fa-users text-2xl mb-2 text-blue-200"></i>
                                    <p class="font-semibold">Équipe expérimentée</p>
                                </div>
                                <div class="bg-white bg-opacity-10 rounded-lg p-4 backdrop-blur-sm border border-white/20">
                                    <i class="fas fa-chart-line text-2xl mb-2 text-blue-200"></i>
                                    <p class="font-semibold">Croissance continue</p>
                                </div>
                                <div class="bg-white bg-opacity-10 rounded-lg p-4 backdrop-blur-sm border border-white/20">
                                    <i class="fas fa-shield-alt text-2xl mb-2 text-blue-200"></i>
                                    <p class="font-semibold">Sécurité prioritaire</p>
                                </div>
                                <div class="bg-white bg-opacity-10 rounded-lg p-4 backdrop-blur-sm border border-white/20">
                                    <i class="fas fa-handshake text-2xl mb-2 text-blue-200"></i>
                                    <p class="font-semibold">Support 24/7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        
        <footer class="w-full lg:max-w-4xl max-w-[335px] mt-8 text-center text-sm text-blue-800 dark:text-blue-300">
            <p>© {{ date('Y') }} Recrutement Chauffeurs. Tous droits réservés.</p>
        </footer>
    </body>
</html>