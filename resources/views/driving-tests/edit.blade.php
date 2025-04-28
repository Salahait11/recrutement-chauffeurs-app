{{-- resources/views/driving_tests/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Messages Flash -->
    @if (session('success'))
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
    @if (session('error'))
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

    <!-- En-tête avec titre -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                             <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Modifier le Test de Conduite
                        </div>
                    </h2>
                     {{-- Ajout conditionnel du nom du candidat et date --}}
                    @if($drivingTest->candidate)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Test de {{ $drivingTest->candidate->first_name }} {{ $drivingTest->candidate->last_name }} du {{ $drivingTest->test_date?->format('d/m/Y H:i') ?? 'Date inconnue' }}
                    </p>
                    @endif
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3">
                    {{-- Lien vers Show ou Index selon préférence --}}
                    <a href="{{ route('driving-tests.show', $drivingTest->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour aux détails
                    </a>
                    {{-- Le bouton Enregistrer est dans le formulaire en bas --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <form action="{{ route('driving-tests.update', $drivingTest->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            @if(request('mode') === 'result')
                <input type="hidden" name="mode" value="result">
            @endif

            @unless(request('mode') === 'result')
            <!-- Informations Générales -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations Générales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Candidat --}}
                    <div>
                        <label for="candidate_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Candidat <span class="text-red-500">*</span></label>
                        <select id="candidate_id" name="candidate_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">-- {{ __('Sélectionner un candidat') }} --</option>
                            @foreach($candidates as $candidate)
                                <option value="{{ $candidate->id }}" @selected(old('candidate_id', $drivingTest->candidate_id) == $candidate->id)>
                                    {{ $candidate->first_name }} {{ $candidate->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('candidate_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Évaluateur --}}
                    <div>
                        <label for="evaluator_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Évaluateur <span class="text-red-500">*</span></label>
                        <select id="evaluator_id" name="evaluator_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">-- {{ __('Sélectionner un évaluateur') }} --</option>
                            @foreach($evaluators as $evaluator)
                                <option value="{{ $evaluator->id }}" @selected(old('evaluator_id', $drivingTest->evaluator_id) == $evaluator->id)>
                                    {{ $evaluator->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('evaluator_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                     {{-- Date et Heure --}}
                    <div>
                        <label for="test_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date et Heure du Test <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="test_date" name="test_date" required
                               value="{{ old('test_date', $drivingTest->test_date?->format('Y-m-d\TH:i')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('test_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Véhicule (Optionnel) --}}
                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Véhicule Utilisé</label>
                        <select id="vehicle_id" name="vehicle_id"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">-- {{ __('Sélectionner un véhicule') }} --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $drivingTest->vehicle_id) == $vehicle->id)>
                                    {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                 {{-- Itinéraire / Détails --}}
                <div>
                    <label for="route_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Itinéraire / Conditions Spécifiques</label>
                    <textarea id="route_details" name="route_details" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm"
                              >{{ old('route_details', $drivingTest->route_details) }}</textarea>
                    @error('route_details')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endunless
            <hr class="dark:border-gray-700 my-4">

            @if(request('mode') === 'result')
            <!-- Résultats du Test -->
             <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Résultats du Test</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Statut --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="planifie" @selected(old('status', $drivingTest->status) === 'planifie')>{{ __('Planifié') }}</option>
                            <option value="reussi" @selected(old('status', $drivingTest->status) === 'reussi')>{{ __('Réussi') }}</option>
                            <option value="echoue" @selected(old('status', $drivingTest->status) === 'echoue')>{{ __('Échoué') }}</option>
                            <option value="annule" @selected(old('status', $drivingTest->status) === 'annule')>{{ __('Annulé') }}</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Score --}}
                    <div>
                        <label for="score" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Score (sur 100)</label>
                        <input type="number" id="score" name="score" min="0" max="100" value="{{ old('score', $drivingTest->score) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('score')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Résultat (Passed) --}}
                    <div>
                        <label for="passed" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Résultat Final</label>
                        <select id="passed" name="passed"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="" @selected(old('passed', $drivingTest->passed) === null || old('passed', $drivingTest->passed) === '')>-- {{ __('Non défini / Non applicable') }} --</option>
                            <option value="1" @selected(old('passed', $drivingTest->passed) === true || old('passed', $drivingTest->passed) === '1')>{{ __('Réussi') }}</option>
                            <option value="0" @selected((old('passed', $drivingTest->passed) === false || old('passed', $drivingTest->passed) === '0') && old('passed', $drivingTest->passed) !== null && old('passed', $drivingTest->passed) !== '')>{{ __('Échoué') }}</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __("Seulement pertinent si statut = 'Terminé', 'Réussi' ou 'Échoué'.") }}</p>
                        @error('passed')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                 </div>

                {{-- Résumé des Résultats / Commentaires --}}
                <div>
                    <label for="results_summary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Résumé des Résultats / Commentaires</label>
                    <textarea id="results_summary" name="results_summary" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">{{ old('results_summary', $drivingTest->results_summary) }}</textarea>
                    @error('results_summary')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Recommandations --}}
                <div>
                    <label for="recommendations" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recommandations</label>
                    <textarea id="recommendations" name="recommendations" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">{{ old('recommendations', $drivingTest->recommendations) }}</textarea>
                    @error('recommendations')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
             </div>
            @endif

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                {{-- Lien Annuler vers la page Show --}}
                 <a href="{{ route('driving-tests.show', $drivingTest->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- Script pour la gestion des champs conditionnels --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const scoreInput = document.getElementById('score');
        const passedSelect = document.getElementById('passed');

        function updateFields() {
            const status = statusSelect.value;
            const showResults = ['completed', 'reussi', 'echoue'].includes(status);
            
            // Mettre à jour la visibilité et l'obligation des champs
            if (scoreInput) {
                scoreInput.required = showResults;
            }
            if (passedSelect) {
                passedSelect.required = showResults;
            }
        }

        // Écouter les changements de statut
        statusSelect.addEventListener('change', updateFields);
        
        // Initialiser l'état des champs
        updateFields();
    });
</script>