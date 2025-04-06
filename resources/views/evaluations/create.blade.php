{{-- resources/views/evaluations/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Adapte le titre en fonction de ce qui est évalué --}}
            @if(isset($interview))
                {{ __('Évaluer l\'Entretien pour :') }} {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                ({{ $interview->interview_date->format('d/m/Y H:i') }})
            @elseif(isset($drivingTest))
                 {{ __('Évaluer le Test de Conduite pour :') }} {{ $drivingTest->candidate->first_name }} {{ $drivingTest->candidate->last_name }}
                ({{ $drivingTest->test_date->format('d/m/Y H:i') }})
            @else
                {{ __('Nouvelle Évaluation') }} {{-- Cas par défaut si ni interview ni test n'est passé --}}
            @endif
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

                    <form method="POST" action="{{ route('evaluations.store') }}">
                        @csrf
                        {{-- Champs cachés pour lier l'évaluation à l'entretien OU au test --}}
                        @if(isset($interview))
                            <input type="hidden" name="interview_id" value="{{ $interview->id }}">
                            <input type="hidden" name="candidate_id" value="{{ $interview->candidate_id }}">
                        @elseif(isset($drivingTest))
                             <input type="hidden" name="driving_test_id" value="{{ $drivingTest->id }}">
                             <input type="hidden" name="candidate_id" value="{{ $drivingTest->candidate_id }}">
                        @else
                             {{-- Peut-être ajouter un select pour choisir le candidat si on arrive ici ? --}}
                             <p class="text-red-500">Erreur: Impossible de déterminer l'élément à évaluer.</p>
                        @endif

                        <div class="space-y-8">

                            {{-- Section des Critères --}}
                            <div>
                                <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Critères d'Évaluation (Noter de 1 à 5)</h3>
                                <div class="space-y-6">
                                    @if(isset($criteria) && $criteria->count() > 0)
                                        @foreach ($criteria as $criterion)
                                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                                                <label class="block font-medium text-md text-gray-700 dark:text-gray-300">{{ $criterion->name }}</label>
                                                @if($criterion->description)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $criterion->description }}</p>
                                                @endif

                                                {{-- Champ Note (Radio buttons) --}}
                                                <div class="flex space-x-4 mt-2 mb-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <label class="flex items-center space-x-1">
                                                            <input type="radio" name="ratings[{{ $criterion->id }}]" value="{{ $i }}" class="rounded-full dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ old('ratings.'.$criterion->id) == $i ? 'checked' : '' }} required>
                                                            <span class="text-sm">{{ $i }}</span>
                                                        </label>
                                                    @endfor
                                                </div>
                                                 {{-- Afficher erreur spécifique à ce critère --}}
                                                 @error('ratings.'.$criterion->id)
                                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                                 @enderror

                                                {{-- Champ Commentaire --}}
                                                <div>
                                                    <label for="comments_{{ $criterion->id }}" class="sr-only">{{ __('Commentaire pour') }} {{ $criterion->name }}</label>
                                                    <textarea name="comments[{{ $criterion->id }}]" id="comments_{{ $criterion->id }}" rows="2" placeholder="Commentaire (optionnel)..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 text-sm">{{ old('comments.'.$criterion->id) }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-center text-gray-500 dark:text-gray-400">Aucun critère d'évaluation n'a été configuré ou passé à cette vue.</p>
                                    @endif
                                </div>
                            </div>

                            <hr class="dark:border-gray-600">

                            {{-- Section Conclusion & Recommandation --}}
                            <div>
                                <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Conclusion Générale</h3>

                                {{-- Conclusion --}}
                                <div>
                                    <label for="conclusion" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Conclusion / Résumé') }}</label>
                                    <textarea name="conclusion" id="conclusion" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('conclusion') }}</textarea>
                                    @error('conclusion') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Recommandation --}}
                                <div class="mt-4">
                                    <label for="recommendation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Recommandation') }}</label>
                                    <select name="recommendation" id="recommendation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="positive" {{ old('recommendation') == 'positive' ? 'selected' : '' }}>Positive (Poursuivre)</option>
                                        <option value="neutral" {{ old('recommendation') == 'neutral' ? 'selected' : '' }}>Neutre (Plus d'infos nécessaires)</option>
                                        <option value="negative" {{ old('recommendation') == 'negative' ? 'selected' : '' }}>Négative (Arrêter processus)</option>
                                    </select>
                                     @error('recommendation') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Note Globale (Optionnel) --}}
                                {{--
                                <div class="mt-4">
                                    <label for="overall_rating" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Note Globale (1-5)') }}</label>
                                    <input type="number" name="overall_rating" id="overall_rating" min="1" max="5" class="block mt-1 w-20 rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('overall_rating') }}">
                                </div>
                                 --}}

                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="flex items-center justify-end mt-8">
                            {{-- Lien Annuler dynamique --}}
                            <a href="{{ isset($interview) ? route('interviews.show', $interview->id) : (isset($drivingTest) ? route('driving-tests.show', $drivingTest->id) : route('evaluations.index')) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Enregistrer l\'Évaluation') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>