{{-- resources/views/driving_tests/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails du Test de Conduite') }}
                @if($drivingTest->candidate)
                     {{ __('pour') }} {{ $drivingTest->candidate->first_name }} {{ $drivingTest->candidate->last_name }}
                     ({{ $drivingTest->test_date->format('d/m/Y H:i') }})
                @endif
            </h2>
            {{-- Bouton pour retourner à la liste --}}
            <a href="{{ route('driving-tests.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                     {{-- Afficher les messages flash --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Détails du test --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="md:col-span-1 font-semibold">{{ __('Candidat') }}</div>
                        <div class="md:col-span-2">
                            @if($drivingTest->candidate)
                                <a href="{{ route('candidates.show', $drivingTest->candidate->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $drivingTest->candidate->first_name }} {{ $drivingTest->candidate->last_name }}
                                </a>
                            @else
                                <span class="italic text-gray-500">Candidat non trouvé</span>
                            @endif
                        </div>

                        <div class="md:col-span-1 font-semibold">{{ __('Date et Heure') }}</div>
                        <div class="md:col-span-2">{{ $drivingTest->test_date->format('d/m/Y H:i') }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Véhicule') }}</div>
                        <div class="md:col-span-2">{{ $drivingTest->vehicle_type ?? 'Non spécifié' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Évaluateur') }}</div>
                        <div class="md:col-span-2">{{ $drivingTest->evaluator->name ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Statut') }}</div>
                        <div class="md:col-span-2">

                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @switch($drivingTest->status)
                                    @case('completed') bg-green-100 text-green-800 @break
                                    @case('canceled') bg-red-100 text-red-800 @break
                                    @default bg-blue-100 text-blue-800 {{-- scheduled --}}
                                @endswitch
                            ">
                                {{-- Use match expression for cleaner status display --}}
                                {{-- Changed from 'completed', 'canceled', 'default' to the defined constants --}}
                                {{ ucfirst(match($drivingTest->status){ {{$drivingTest::STATUS_SCHEDULED}} => 'Planifié', {{$drivingTest::STATUS_PASSED}} => 'Réussi', {{$drivingTest::STATUS_FAILED}} => 'Échoué', {{$drivingTest::STATUS_CANCELED}} => 'Annulé', default => 'Inconnu' }) }}
                            </span>
                        </div>

                        <div class="md:col-span-1 font-semibold">{{ __('Itinéraire / Conditions') }}</div>
                        <div class="md:col-span-2 whitespace-pre-wrap">{{ $drivingTest->route_details ?? 'Non spécifié' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Date de Planification') }}</div>
                        <div class="md:col-span-2">{{ $drivingTest->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    {{-- Section Résultats & Évaluation --}}
                     <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Résultats et Évaluation</h3>
                     @if($drivingTest->status === {{$drivingTest::STATUS_PASSED}})
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="md:col-span-1 font-semibold">{{ __('Résultat') }}</div>
                            <div class="md:col-span-2">
                                @if($drivingTest->passed === true)
                                    <span class="text-green-600 font-bold">Réussi</span>
                                @elseif($drivingTest->passed === false)
                                    <span class="text-red-600 font-bold">Échoué</span>
                                @else
                                    <span class="text-gray-500 italic">Non défini</span>
                                @endif
                            </div>
                             <div class="md:col-span-1 font-semibold">{{ __('Résumé des Résultats') }}</div>
                            <div class="md:col-span-2 whitespace-pre-wrap">{{ $drivingTest->results_summary ?? 'Aucun résumé' }}</div>
                        </div>

                        {{-- Liens vers Évaluation --}}
                        <div class="mt-4">
                             @if($drivingTest->evaluations()->exists())
                                <a href="{{ route('evaluations.show', $drivingTest->evaluations()->first()->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Voir l'Évaluation
                                </a>
                            @else
                                {{-- Afficher le bouton Évaluer seulement si le test est terminé ? --}}
                                <a href="{{ route('driving-tests.evaluations.create', $drivingTest->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Évaluer ce Test
                                </a>
                            @endif
                        </div>

                     @else
                        <div class="text-center text-gray-500 dark:text-gray-400 italic py-4">
                            Les résultats et l'évaluation ne sont disponibles qu'une fois le test marqué comme terminé.
                            <br>
                             <a href="{{ route('driving-tests.edit', $drivingTest->id) }}" class="mt-2 inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Enregistrer les Résultats / Modifier le Test
                            </a>
                        </div>
                     @endif

                    <hr class="dark:border-gray-600 mt-6">

                    {{-- Zone pour les actions sur le test lui-même --}}
                    <div class="flex justify-end space-x-3 mt-6">
                         <a href="{{ route('driving-tests.edit', $drivingTest->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Modifier') }}
                        </a>
                        <form method="POST" action="{{ route('driving-tests.destroy', $drivingTest->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce test ?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Supprimer') }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>