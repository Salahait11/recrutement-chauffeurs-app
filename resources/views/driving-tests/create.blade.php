{{-- resources/views/driving_tests/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec titre -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône Volant (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.324h5.365a.562.562 0 0 1 .342.948l-4.344 3.132a.563.563 0 0 0-.182.557l1.635 5.058a.562.562 0 0 1-.812.622l-4.344-3.132a.563.563 0 0 0-.65 0L6.08 21.233a.562.562 0 0 1-.812-.622l1.635-5.058a.563.563 0 0 0-.182-.557l-4.344-3.132a.562.562 0 0 1 .342-.948H8.88a.563.563 0 0 0 .475-.324L11.48 3.5Z" /> {{-- Remplacé par une autre icône pertinente si besoin --}}
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6.75 6.75 0 1 0 0-13.5 6.75 6.75 0 0 0 0 13.5Z" />
                            </svg>
                            Planifier un Nouveau Test de Conduite
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('driving-tests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        {{-- Affichage des erreurs générales (optionnel, différent du modèle mais peut être utile) --}}
        {{-- @if ($errors->any())
            <div class="p-4 mb-0 border-b border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-900/30">
                 <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Il y a {{ $errors->count() }} erreur(s) avec votre soumission</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                        <ul role="list" class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}

        <form action="{{ route('driving-tests.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Détails du Test -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Détails du Test</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Candidat --}}
                    <div>
                        <label for="candidate_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Candidat <span class="text-red-500">*</span></label>
                        <select id="candidate_id" name="candidate_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">-- {{ __('Sélectionner un candidat') }} --</option>
                            @foreach($candidates as $candidate)
                                <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>
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
                                <option value="{{ $evaluator->id }}" {{ old('evaluator_id') == $evaluator->id ? 'selected' : '' }}>
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
                        <input type="datetime-local" id="test_date" name="test_date" value="{{ old('test_date') }}" required
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
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Conditions et Statut -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Conditions et Statut</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Statut (Si applicable à la création) --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut Initial</label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="planifie" {{ old('status', 'planifie') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                            <option value="reussi" {{ old('status') == 'reussi' ? 'selected' : '' }}>Réussi</option>
                            <option value="echoue" {{ old('status') == 'echoue' ? 'selected' : '' }}>Échoué</option>
                            <option value="annule" {{ old('status') == 'annule' ? 'selected' : '' }}>Annulé</option>
                        </select>
                         @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Score (visible uniquement si le test est terminé) --}}
                    <div id="score_field" class="hidden">
                        <label for="score" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Score (sur 100)</label>
                        <input type="number" id="score" name="score" min="0" max="100" value="{{ old('score') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('score')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Résumé des résultats (visible uniquement si le test est terminé) --}}
                <div id="results_summary_field" class="hidden">
                    <label for="results_summary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Résumé des Résultats</label>
                    <textarea id="results_summary" name="results_summary" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">{{ old('results_summary') }}</textarea>
                    @error('results_summary')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Recommandations (visible uniquement si le test est terminé) --}}
                <div id="recommendations_field" class="hidden">
                    <label for="recommendations" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recommandations</label>
                    <textarea id="recommendations" name="recommendations" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">{{ old('recommendations') }}</textarea>
                    @error('recommendations')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Itinéraire / Détails --}}
                <div>
                    <label for="route_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Itinéraire / Conditions</label>
                    <textarea id="route_details" name="route_details" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">{{ old('route_details') }}</textarea>
                    @error('route_details')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('driving-tests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    Planifier le Test
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- Script pour la date minimale et la gestion des champs conditionnels --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration de la date minimale
        const now = new Date();
        now.setMinutes(now.getMinutes() + 1);
        const year = now.getFullYear();
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const day = now.getDate().toString().padStart(2, '0');
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        
        const testDateInput = document.getElementById('test_date');
        if (testDateInput) {
            testDateInput.setAttribute('min', minDateTime);
        }

        // Gestion des champs conditionnels basée sur le statut
        const statusSelect = document.getElementById('status');
        const scoreField = document.getElementById('score_field');
        const resultsSummaryField = document.getElementById('results_summary_field');
        const recommendationsField = document.getElementById('recommendations_field');

        function toggleFields() {
            const status = statusSelect.value;
            const showResults = ['reussi', 'echoue'].includes(status);
            
            scoreField.classList.toggle('hidden', !showResults);
            resultsSummaryField.classList.toggle('hidden', !showResults);
            recommendationsField.classList.toggle('hidden', !showResults);

            // Rendre le score obligatoire si le test est terminé
            const scoreInput = document.getElementById('score');
            if (scoreInput) {
                scoreInput.required = showResults;
            }
        }

        // Écouter les changements de statut
        statusSelect.addEventListener('change', toggleFields);
        
        // Initialiser l'état des champs
        toggleFields();
    });
</script>