{{-- resources/views/evaluations/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300">
                    @if(isset($interview))
                        {{ __('Créer une évaluation d\'entretien') }}
                    @else
                        {{ __('Créer une évaluation de test de conduite') }}
                    @endif
                </h2>
                <a href="{{ isset($interview) ? route('interviews.show', $interview) : route('driving-tests.show', $drivingTest) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Retour') }}
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <form action="{{ isset($interview) ? route('interviews.evaluations.store', $interview) : route('driving-tests.evaluations.store', $drivingTest) }}" method="POST" class="p-6">
                @csrf
                @if(isset($interview))
                    <input type="hidden" name="interview_id" value="{{ $interview->id }}">
                    <input type="hidden" name="candidate_id" value="{{ $interview->candidate->id }}">
                @else
                    <input type="hidden" name="driving_test_id" value="{{ $drivingTest->id }}">
                    <input type="hidden" name="candidate_id" value="{{ $drivingTest->candidate->id }}">
                @endif

                <!-- Informations du candidat -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Informations du candidat') }}
                    </h3>
                    <div class="mt-2">
                        <p><strong>Nom :</strong> {{ isset($interview) ? $interview->candidate->getFullName() : $drivingTest->candidate->getFullName() }}</p>
                        <p><strong>Email :</strong> {{ isset($interview) ? $interview->candidate->email : $drivingTest->candidate->email }}</p>
                        <p><strong>Téléphone :</strong> {{ isset($interview) ? $interview->candidate->phone : $drivingTest->candidate->phone }}</p>
                    </div>
                </div>

                <!-- Critères d'évaluation -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Critères d\'évaluation') }}
                    </h3>
                    <div class="space-y-6">
                        @foreach($criteria as $criterion)
                            <div class="border dark:border-gray-700 rounded-lg p-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $criterion->name }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    @if($criterion->description)
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $criterion->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('Note') }}
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select name="responses[{{ $criterion->id }}][rating]" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm" required>
                                            <option value="">{{ __('Sélectionnez une note') }}</option>
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ old("responses.{$criterion->id}.rating") == $i ? 'selected' : '' }}>
                                                    {{ $i }}/5
                                                </option>
                                            @endfor
                                        </select>
                                        @error("responses.{$criterion->id}.rating")
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('Commentaires') }}
                                        </label>
                                        <textarea name="responses[{{ $criterion->id }}][comments]" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">{{ old("responses.{$criterion->id}.comments") }}</textarea>
                                        @error("responses.{$criterion->id}.comments")
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" name="responses[{{ $criterion->id }}][criterion_id]" value="{{ $criterion->id }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recommandation -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Recommandation') }}
                        <span class="text-red-500">*</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="inline-flex items-center">
                                <input type="radio" name="recommendation" value="positive" class="rounded-full border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('recommendation') === 'positive' ? 'checked' : '' }} required>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Positive') }}</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="radio" name="recommendation" value="neutral" class="rounded-full border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('recommendation') === 'neutral' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Neutre') }}</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="radio" name="recommendation" value="negative" class="rounded-full border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('recommendation') === 'negative' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Négative') }}</span>
                            </label>
                        </div>
                    </div>
                    @error('recommendation')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conclusion -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Conclusion') }}
                    </h3>
                    <textarea name="conclusion" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">{{ old('conclusion') }}</textarea>
                    @error('conclusion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ isset($interview) ? route('interviews.show', $interview) : route('driving-tests.show', $drivingTest) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Annuler') }}
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Enregistrer l\'évaluation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection