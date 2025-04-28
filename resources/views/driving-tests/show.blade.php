{{-- resources/views/driving_tests/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Messages Flash -->
    @if(session('success'))
        {{-- Style similaire au message d'erreur, mais en vert --}}
        <div class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800">
             <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
                        </div>
                    @endif

    <!-- En-tête avec titre et actions -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône Document/Clipboard (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 6.75 6h.75c.414 0 .75.336.75.75S7.914 7.5 7.5 7.5H6.75A2.25 2.25 0 0 1 4.5 5.25V18a2.25 2.25 0 0 0 2.25 2.25h10.5A2.25 2.25 0 0 0 19.5 18v-2.625" />
                            </svg>
                            Détails du Test de Conduite
                        </div>
                    </h2>
                     {{-- Ajout conditionnel du nom du candidat et date --}}
                    @if($drivingTest->candidate)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pour {{ $drivingTest->candidate->first_name }} {{ $drivingTest->candidate->last_name }} - Test du {{ $drivingTest->test_date->format('d/m/Y H:i') }}
                    </p>
                    @endif
                </div>
                <div class="mt-4 flex flex-shrink-0 md:ml-4 md:mt-0 space-x-3">
                    <a href="{{ route('driving-tests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la liste
                    </a>
                    <a href="{{ route('driving-tests.edit', $drivingTest->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
                    </a>
                    @if($drivingTest->status === 'planifie')
                    <a href="{{ route('driving-tests.edit', ['drivingTest' => $drivingTest->id, 'mode' => 'result']) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Terminer le test
                    </a>
                    @endif
                    {{-- Bouton Supprimer (Formulaire) --}}
                    <form action="{{ route('driving-tests.destroy', $drivingTest->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce test ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations du Test -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6 space-y-6">
            <!-- Informations du Test -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations du Test</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Candidat</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            @if($drivingTest->candidate)
                                <a href="{{ route('candidates.show', $drivingTest->candidate->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $drivingTest->candidate->first_name }} {{ $drivingTest->candidate->last_name }}
                                </a>
                            @else
                                <span class="italic text-gray-500">Candidat non trouvé</span>
                            @endif
                        </p>
                        </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date et Heure</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $drivingTest->test_date->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Véhicule</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                             @if($drivingTest->vehicle)
                                {{ $drivingTest->vehicle->brand }} {{ $drivingTest->vehicle->model }} ({{ $drivingTest->vehicle->plate_number }})
                            @else
                                {{ __('Non spécifié') }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Évaluateur</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $drivingTest->evaluator->name ?? 'N/A' }}</p>
                    </div>

                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
                        <p class="mt-1">
                            @php
                                $statusClass = '';
                                $statusTextClass = '';
                                switch($drivingTest->status) {
                                    case 'planifie':
                                        $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                        $statusLabel = __('Planifié');
                                        break;
                                    case 'reussi':
                                        $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                         $statusLabel = __('Réussi');
                                        break;
                                    case 'echoue':
                                        $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                         $statusLabel = __('Échoué');
                                        break;
                                    case 'annule':
                                        $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                         $statusLabel = __('Annulé');
                                        break;
                                    default:
                                        $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                        $statusLabel = ucfirst($drivingTest->status);
                                }
                            @endphp
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </p>
                        </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Planification</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $drivingTest->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                 {{-- Itinéraire / Conditions affiché sur toute la largeur si présent --}}
                 @if($drivingTest->route_details)
                 <div class="pt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Itinéraire / Conditions</label>
                    <div class="mt-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                         <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $drivingTest->route_details }}</p>
                    </div>
                </div>
                @endif
            </div>

            <hr class="dark:border-gray-700">

            <!-- Résultats -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Résultats</h3>

                @if($drivingTest->status === 'reussi' || $drivingTest->status === 'echoue')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Résultat Final</label>
                            <p class="mt-1 text-sm font-semibold
                                @if($drivingTest->passed === true) text-green-600 dark:text-green-400 @endif
                                @if($drivingTest->passed === false) text-red-600 dark:text-red-400 @endif
                            ">
                                @if($drivingTest->passed === true)
                                    {{ __('Réussi') }}
                                @elseif($drivingTest->passed === false)
                                    {{ __('Échoué') }}
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 italic">{{ __('Non défini') }}</span>
                                @endif
                            </p>
                            </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Score</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $drivingTest->score ?? 'N/A' }}/100
                            </p>
                        </div>
                    </div>
                    @if($drivingTest->results_summary)
                        <div class="pt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Résumé des Résultats</label>
                             <div class="mt-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $drivingTest->results_summary }}</p>
                            </div>
                        </div>
                    @else
                         <p class="text-sm text-gray-500 dark:text-gray-400 italic">Aucun résumé des résultats disponible.</p>
                    @endif
                     {{-- Bouton Créer Évaluation si test réussi/échoué --}}
                    @if(in_array($drivingTest->status, [App\Models\DrivingTest::STATUS_REUSSI, App\Models\DrivingTest::STATUS_ECHOUE]) && !$drivingTest->evaluations->count())
                        <div class="pt-4 flex justify-start">
                            <a href="{{ route('driving-tests.evaluations.create', $drivingTest) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <svg class="-ml-0.5 mr-1.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                </svg>
                                {{ __('Créer une évaluation') }}
                            </a>
                        </div>
                    @endif

                @elseif($drivingTest->status === 'completed') {{-- Statut 'Terminé' mais sans résultat enregistré --}}
                    <div class="text-center text-gray-500 dark:text-gray-400 italic py-4 border border-dashed border-yellow-400 dark:border-yellow-600 rounded-md bg-yellow-50 dark:bg-yellow-900/20">
                         <p class="mb-2">{{__("Le test est marqué comme terminé. Veuillez enregistrer les résultats.")}}</p>
                         <a href="{{ route('driving-tests.edit', $drivingTest->id) }}?section=results" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 1.687-1.688a1.875 1.875 0 0 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            {{__("Enregistrer le résultat")}}
                        </a>
                    </div>
                 @elseif($drivingTest->status === 'scheduled')
                    <div class="text-center text-gray-500 dark:text-gray-400 italic py-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-md">
                        {{__("Les résultats seront disponibles une fois le test marqué comme terminé.")}}
                        {{-- On pourrait aussi mettre un bouton pour marquer comme terminé ici --}}
                         {{-- <a href="{{ route('driving-tests.edit', $drivingTest->id) }}" class="mt-2 inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{__("Modifier le Test")}}
                        </a> --}}
                    </div>
                 @else {{-- Cas Annulé ou autre --}}
                     <p class="text-sm text-gray-500 dark:text-gray-400 italic">Les résultats ne sont pas applicables pour ce statut de test.</p>
                @endif
            </div>

            <hr class="dark:border-gray-700">

            <!-- Évaluations -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Évaluations</h3>
                </div>

                @forelse($drivingTest->evaluations as $evaluation)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Évaluation du {{ $evaluation->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Recommandation: 
                                    <span class="font-medium
                                        @if($evaluation->recommendation === 'positive') text-green-600 dark:text-green-400
                                        @elseif($evaluation->recommendation === 'negative') text-red-600 dark:text-red-400
                                        @else text-yellow-600 dark:text-yellow-400
                                        @endif">
                                        {{ ucfirst($evaluation->recommendation) }}
                                    </span>
                                </p>
                            </div>
                            <a href="{{ route('evaluations.show', $evaluation) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Voir l\'évaluation') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">{{ __('Aucune évaluation disponible.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection