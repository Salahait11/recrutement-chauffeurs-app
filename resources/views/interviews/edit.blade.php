@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300">
                    {{ __('Modifier l\'Entretien') }}
                </h2>
                <a href="{{ route('interviews.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Retour à la liste') }}
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('interviews.update', $interview) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Informations du candidat -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Informations du candidat') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="candidate_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Candidat') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="candidate_id" id="candidate_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                    <option value="">{{ __('Sélectionnez un candidat') }}</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}" {{ old('candidate_id', $interview->candidate_id) == $candidate->id ? 'selected' : '' }}>
                                            {{ $candidate->first_name }} {{ $candidate->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('candidate_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Type d\'entretien') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="type" id="type" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                    <option value="">{{ __('Sélectionnez un type') }}</option>
                                    <option value="initial" {{ old('type', $interview->type) == 'initial' ? 'selected' : '' }}>{{ __('Entretien initial') }}</option>
                                    <option value="technique" {{ old('type', $interview->type) == 'technique' ? 'selected' : '' }}>{{ __('Entretien technique') }}</option>
                                    <option value="final" {{ old('type', $interview->type) == 'final' ? 'selected' : '' }}>{{ __('Entretien final') }}</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Détails de l'entretien -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Détails de l\'entretien') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="interview_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Date et heure') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="interview_date" id="interview_date" value="{{ old('interview_date', $interview->interview_date->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                @error('interview_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="interviewer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Interviewer') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="interviewer_id" id="interviewer_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                    <option value="">{{ __('Sélectionnez un interviewer') }}</option>
                                    @foreach($interviewers as $interviewer)
                                        <option value="{{ $interviewer->id }}" {{ old('interviewer_id', $interview->interviewer_id) == $interviewer->id ? 'selected' : '' }}>
                                            {{ $interviewer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('interviewer_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Durée (en minutes)') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="duration" id="duration" value="{{ old('duration', $interview->duration) }}" required min="15" max="240" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                @error('duration')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Statut') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                    <option value="planifié" {{ old('status', $interview->status) == 'planifié' ? 'selected' : '' }}>{{ __('Planifié') }}</option>
                                    <option value="en cours" {{ old('status', $interview->status) == 'en cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                    <option value="terminé" {{ old('status', $interview->status) == 'terminé' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                                    <option value="annulé" {{ old('status', $interview->status) == 'annulé' ? 'selected' : '' }}>{{ __('Annulé') }}</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Notes') }}
                            </label>
                            <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">{{ old('notes', $interview->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('interviews.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Annuler') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Mettre à jour l\'entretien') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection