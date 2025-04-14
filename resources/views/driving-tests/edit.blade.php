{{-- resources/views/driving_tests/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier Test Conduite :') }}
            {{-- Gérer le cas où le candidat ou son nom/prénom est null --}}
            {{ $drivingTest->candidate?->full_name ?? ($drivingTest->candidate?->first_name . ' ' . $drivingTest->candidate?->last_name) ?? 'Candidat inconnu' }}
            du
            {{-- Gérer le cas où la date est nulle --}}
            {{ $drivingTest->test_date?->format('d/m/Y H:i') ?? 'Date inconnue' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Bloc d'affichage des erreurs de validation --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">{{ __('Oups ! Il y a des erreurs.') }}</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Bloc de message de succès/erreur de session --}}
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                         <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                             {{ session('error') }}
                         </div>
                    @endif


                    <form method="POST" action="{{ route('driving-tests.update', $drivingTest->id) }}">
                        @csrf {{-- Protection CSRF --}}
                        @method('PUT') {{-- Méthode HTTP pour la mise à jour --}}

                        <div class="space-y-6">
                            {{-- Champ Candidat --}}
                            <div>
                                <x-input-label for="candidate_id" :value="__('Candidat')" /> <span class="text-red-500">*</span>
                                <x-select-input id="candidate_id" name="candidate_id" class="block mt-1 w-full" required>
                                    <option value="">-- {{ __('Sélectionner un candidat') }} --</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}" @selected(old('candidate_id', $drivingTest->candidate_id) == $candidate->id)>
                                            {{ $candidate->first_name }} {{ $candidate->last_name }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('candidate_id')" class="mt-2" />
                            </div>

                            {{-- Champ Évaluateur --}}
                            <div>
                                <x-input-label for="evaluator_id" :value="__('Évaluateur')" /> <span class="text-red-500">*</span>
                                <x-select-input id="evaluator_id" name="evaluator_id" class="block mt-1 w-full" required>
                                    <option value="">-- {{ __('Sélectionner un évaluateur') }} --</option>
                                    @foreach($evaluators as $evaluator)
                                        <option value="{{ $evaluator->id }}" @selected(old('evaluator_id', $drivingTest->evaluator_id) == $evaluator->id)>
                                            {{ $evaluator->name }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('evaluator_id')" class="mt-2" />
                            </div>

                             {{-- Champ Véhicule --}}
                            <div>
                                <x-input-label for="vehicle_id" :value="__('Véhicule Utilisé')" />
                                <x-select-input id="vehicle_id" name="vehicle_id" class="block mt-1 w-full">
                                    <option value="">-- {{ __('Sélectionner un véhicule (Optionnel)') }} --</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $drivingTest->vehicle_id) == $vehicle->id)>
                                            {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('vehicle_id')" class="mt-2" />
                            </div>

                            {{-- Champ Date et Heure --}}
                            <div>
                                <x-input-label for="test_date" :value="__('Date et Heure du Test')" /> <span class="text-red-500">*</span>
                                <x-text-input id="test_date" name="test_date" type="datetime-local" class="block mt-1 w-full"
                                              :value="old('test_date', $drivingTest->test_date?->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('test_date')" class="mt-2" />
                            </div>

                            {{-- Champ Itinéraire / Conditions --}}
                            <div>
                                <x-input-label for="route_details" :value="__('Itinéraire / Conditions Spécifiques')" />
                                <textarea id="route_details" name="route_details" rows="3"
                                          class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                          >{{ old('route_details', $drivingTest->route_details) }}</textarea>
                                <x-input-error :messages="$errors->get('route_details')" class="mt-2" />
                            </div>

                            <hr class="dark:border-gray-700 my-4"> {{-- Séparateur visuel --}}
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Résultats du Test') }}</h3>

                            {{-- Champ Statut --}}
                            <div>
                                <x-input-label for="status" :value="__('Statut du Test')" /> <span class="text-red-500">*</span>
                                {{-- $statuses contient ['scheduled', 'completed', 'canceled'] --}}
                                <x-select-input id="status" name="status" class="block mt-1 w-full" required>
                                    @foreach($statuses as $statusValue)
                                        <option value="{{ $statusValue }}" @selected(old('status', $drivingTest->status) == $statusValue)>
                                            {{-- Affichage conditionnel du libellé --}}
                                            @if($statusValue === \App\Models\DrivingTest::STATUS_SCHEDULED) {{ __('Planifié') }}
                                            @elseif($statusValue === \App\Models\DrivingTest::STATUS_COMPLETED) {{ __('Terminé') }}
                                            @elseif($statusValue === \App\Models\DrivingTest::STATUS_CANCELED) {{ __('Annulé') }}
                                            @else {{ ucfirst($statusValue) }} {{-- Fallback --}}
                                            @endif
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                             {{-- Champ Résultat (Passed) - Conditionnel au statut 'completed' --}}
                            <div>
                                <x-input-label for="passed" :value="__('Résultat (si statut = Terminé)')" />
                                {{-- 'passed' est booléen (true/1, false/0) ou null --}}
                                <x-select-input id="passed" name="passed" class="block mt-1 w-full">
                                    <option value="">-- {{ __('Non applicable ou non défini') }} --</option>
                                    {{-- Comparaison stricte pour true/'1' --}}
                                    <option value="1" @selected(old('passed', $drivingTest->passed) === true || old('passed', $drivingTest->passed) === '1')>{{ __('Réussi') }}</option>
                                    {{-- Comparaison stricte pour false/'0', en excluant null --}}
                                    <option value="0" @selected((old('passed', $drivingTest->passed) === false || old('passed', $drivingTest->passed) === '0') && old('passed', $drivingTest->passed) !== null)>{{ __('Échoué') }}</option>
                                </x-select-input>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __("Ce champ n'est pertinent que si le statut est 'Terminé'. Il sera ignoré sinon.") }}</p>
                                <x-input-error :messages="$errors->get('passed')" class="mt-2" />
                            </div>

                            {{-- Champ Résumé des Résultats / Commentaires --}}
                            <div>
                                <x-input-label for="results_summary" :value="__('Résumé des Résultats / Commentaires')" />
                                <textarea id="results_summary" name="results_summary" rows="4"
                                          class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                          >{{ old('results_summary', $drivingTest->results_summary) }}</textarea>
                                <x-input-error :messages="$errors->get('results_summary')" class="mt-2" />
                            </div>

                        </div> {{-- Fin de space-y-6 --}}

                        {{-- Section des boutons --}}
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('driving-tests.show', $drivingTest->id) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <x-primary-button>
                                {{ __('Mettre à Jour le Test') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div> {{-- Fin de p-6 --}}
            </div> {{-- Fin de bg-white --}}
        </div> {{-- Fin de max-w-4xl --}}
    </div> {{-- Fin de py-12 --}}
</x-app-layout>