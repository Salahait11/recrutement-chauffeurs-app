{{-- resources/views/interviews/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Titre modifié pour l'édition --}}
            {{ __('Modifier l\'Entretien') }}
             @if($interview->candidate)
                {{ __('pour') }} {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
             @endif
             {{ __('le') }} {{ $interview->interview_date->format('d/m/Y') }}
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
                    <form method="POST" action="{{ route('interviews.update', $interview->id) }}">
                        @csrf
                        @method('PUT') {{-- Méthode HTTP pour la mise à jour --}}

                        <div class="space-y-6">
                            {{-- Candidat --}}
                            <div>
                                <label for="candidate_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Candidat') }} <span class="text-red-500">*</span></label>
                                <select name="candidate_id" id="candidate_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">-- Sélectionner un candidat --</option>
                                    @foreach($candidates as $candidate)
                                        {{-- Pré-remplissage pour le select --}}
                                        <option value="{{ $candidate->id }}" {{ old('candidate_id', $interview->candidate_id) == $candidate->id ? 'selected' : '' }}>
                                            {{ $candidate->first_name }} {{ $candidate->last_name }} (ID: {{ $candidate->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Interviewer --}}
                            <div>
                                <label for="interviewer_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Intervieweur Principal') }} <span class="text-red-500">*</span></label>
                                <select name="interviewer_id" id="interviewer_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">-- Sélectionner un intervieweur --</option>
                                    @foreach($interviewers as $interviewer)
                                         {{-- Pré-remplissage pour le select --}}
                                        <option value="{{ $interviewer->id }}" {{ old('interviewer_id', $interview->interviewer_id) == $interviewer->id ? 'selected' : '' }}>
                                            {{ $interviewer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Date et Heure --}}
                            <div>
                                <label for="interview_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date et Heure') }} <span class="text-red-500">*</span></label>
                                {{-- Pré-remplissage pour datetime-local --}}
                                <input type="datetime-local" name="interview_date" id="interview_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('interview_date', $interview->interview_date->format('Y-m-d\TH:i')) }}" required>
                                {{-- Note: Le format Y-m-d\TH:i est celui attendu par datetime-local --}}
                            </div>

                            {{-- Type d'entretien --}}
                            <div>
                                <label for="type" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Type d\'entretien') }}</label>
                                {{-- Pré-remplissage --}}
                                <input type="text" name="type" id="type" placeholder="Ex: Téléphonique, RH, Technique..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('type', $interview->type) }}">
                            </div>

                            {{-- Lieu / Lien Visio --}}
                            <div>
                                <label for="location" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Lieu / Lien Visio') }}</label>
                                {{-- Pré-remplissage --}}
                                <input type="text" name="location" id="location" placeholder="Ex: Bureau principal, Salle 3, Lien Zoom..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('location', $interview->location) }}">
                            </div>

                            {{-- Notes Préparation --}}
                            <div>
                                <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Notes Préparation') }}</label>
                                {{-- Pré-remplissage --}}
                                <textarea name="notes" id="notes" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('notes', $interview->notes) }}</textarea>
                            </div>

                            {{-- Champ Statut (Décommenter pour l'activer) --}}
                            {{--
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Statut') }}</label>
                                <select name="status" id="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="scheduled" {{ old('status', $interview->status) == 'scheduled' ? 'selected' : '' }}>Planifié</option>
                                    <option value="completed" {{ old('status', $interview->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                                    <option value="canceled" {{ old('status', $interview->status) == 'canceled' ? 'selected' : '' }}>Annulé</option>
                                    <option value="rescheduled" {{ old('status', $interview->status) == 'rescheduled' ? 'selected' : '' }}>Reporté</option>
                                </select>
                            </div>
                            --}}

                             {{-- Champ Feedback (Décommenter pour l'activer) --}}
                            {{--
                             <div>
                                <label for="feedback" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Compte-rendu / Feedback') }}</label>
                                <textarea name="feedback" id="feedback" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('feedback', $interview->feedback) }}</textarea>
                            </div>
                            --}}

                        </div>

                        {{-- Boutons Annuler et Mettre à Jour --}}
                        <div class="flex items-center justify-end mt-6">
                            {{-- Lien Annuler --}}
                            <a href="{{ route('interviews.show', $interview->id) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
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