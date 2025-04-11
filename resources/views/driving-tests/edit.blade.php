{{-- resources/views/driving_tests/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier le Test de Conduite') }}
             @if($drivingTest->candidate)
                {{ __('pour') }} {{ $drivingTest->candidate->first_name }} {{ $drivingTest->candidate->last_name }}
             @endif
             {{ __('du') }} {{ $drivingTest->test_date->format('d/m/Y') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Afficher les erreurs de validation --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oups!</strong>
                            <span class="block sm:inline">Il y a eu des problèmes avec votre saisie.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulaire d'édition --}}
                    <form method="POST" action="{{ route('driving-tests.update', $drivingTest->id) }}">
                        @csrf
                        @method('PUT') {{-- Méthode HTTP pour la mise à jour --}}

                        <div class="space-y-6">
                            {{-- Candidat --}}
                            <div>
                                <label for="candidate_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Candidat') }} <span class="text-red-500">*</span></label>
                                <select name="candidate_id" id="candidate_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">-- Sélectionner un candidat --</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}" {{ old('candidate_id', $drivingTest->candidate_id) == $candidate->id ? 'selected' : '' }}>
                                            {{ $candidate->first_name }} {{ $candidate->last_name }} (ID: {{ $candidate->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Évaluateur --}}
                            <div>
                                <label for="evaluator_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Évaluateur') }} <span class="text-red-500">*</span></label>
                                <select name="evaluator_id" id="evaluator_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">-- Sélectionner un évaluateur --</option>
                                    @foreach($evaluators as $evaluator)
                                        <option value="{{ $evaluator->id }}" {{ old('evaluator_id', $drivingTest->evaluator_id) == $evaluator->id ? 'selected' : '' }}>
                                            {{ $evaluator->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                             {{-- Véhicule --}}
                            <div>
                                <label for="vehicle_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Véhicule Utilisé') }}</label>
                                <select name="vehicle_id" id="vehicle_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">-- Sélectionner un véhicule (optionnel) --</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $drivingTest->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Date et Heure du Test --}}
                            <div>
                                <label for="test_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date et Heure du Test') }} <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="test_date" id="test_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('test_date', $drivingTest->test_date->format('Y-m-d\TH:i')) }}" required>
                            </div>

                            {{-- Itinéraire / Détails --}}
                            <div>
                                <label for="route_details" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Itinéraire / Conditions du Test') }}</label>
                                <textarea name="route_details" id="route_details" rows="4" placeholder="Décrire l'itinéraire prévu, les manœuvres spécifiques, conditions météo..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('route_details', $drivingTest->route_details) }}</textarea>
                            </div>

                            <hr class="dark:border-gray-600">

                            {{-- Champs pour la complétion du test --}}
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Résultats du Test</h3>

                            {{-- Champ Statut --}}
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Statut du Test') }}</label>
                                <select name="status" id="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="scheduled" {{ old('status', $drivingTest->status) == 'scheduled' ? 'selected' : '' }}>Planifié</option>
                                    <option value="completed" {{ old('status', $drivingTest->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                                    <option value="canceled" {{ old('status', $drivingTest->status) == 'canceled' ? 'selected' : '' }}>Annulé</option>
                                </select>
                            </div>

                             {{-- Champ Résultat (Passed) --}}
                            <div>
                                <label for="passed" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Résultat (si Terminé)') }}</label>
                                <select name="passed" id="passed" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">-- Laisser vide si non terminé/annulé --</option>
                                    {{-- La valeur envoyée sera '1' pour true, '0' pour false --}}
                                    <option value="1" {{ old('passed', $drivingTest->passed) === true ? 'selected' : '' }}>Réussi</option>
                                    <option value="0" {{ old('passed', $drivingTest->passed) === false ? 'selected' : '' }}>Échoué</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Choisir seulement si le statut est "Terminé".</p>
                            </div>

                            {{-- Champ Résumé des Résultats --}}
                            <div>
                                <label for="results_summary" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Résumé des Résultats / Commentaires Généraux') }}</label>
                                <textarea name="results_summary" id="results_summary" rows="4" placeholder="Points forts, points faibles, remarques spécifiques..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('results_summary', $drivingTest->results_summary) }}</textarea>
                            </div>

                        </div>

                        {{-- Boutons Annuler et Mettre à Jour --}}
                        <div class="flex items-center justify-end mt-6">
                            {{-- Lien Annuler --}}
                            <a href="{{ route('driving-tests.show', $drivingTest->id) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            {{-- Bouton Mettre à Jour --}}
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Mettre à Jour') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>