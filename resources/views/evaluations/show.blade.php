{{-- resources/views/evaluations/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages flash -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300">
                    {{ __('Détails de l\'évaluation') }}
                </h2>
                <div class="flex space-x-4">
                    @if($evaluation->interview_id)
                        <a href="{{ route('interviews.show', $evaluation->interview) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Voir l\'entretien') }}
                        </a>
                    @elseif($evaluation->driving_test_id)
                        <a href="{{ route('driving-tests.show', $evaluation->drivingTest) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Voir le test de conduite') }}
                        </a>
                    @endif
                    <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Retour à la liste') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Informations générales -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Informations générales') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Candidat') }}</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">
                                {{ $evaluation->candidate->first_name }} {{ $evaluation->candidate->last_name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Évaluateur') }}</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">
                                {{ $evaluation->evaluator ? $evaluation->evaluator->name : __('Non spécifié') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Date de l\'évaluation') }}</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">
                                {{ $evaluation->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        @if($evaluation->interview_id)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Date de l\'entretien') }}</p>
                                <p class="text-base text-gray-900 dark:text-gray-100">
                                    {{ $evaluation->interview->interview_date->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @elseif($evaluation->driving_test_id)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Date du test de conduite') }}</p>
                                <p class="text-base text-gray-900 dark:text-gray-100">
                                    {{ $evaluation->drivingTest->test_date->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recommandation -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Recommandation') }}
                    </h3>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($evaluation->recommendation === 'positive') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                        @elseif($evaluation->recommendation === 'neutral') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif">
                        {{ ucfirst($evaluation->recommendation) }}
                    </div>
                </div>

                <!-- Critères d'évaluation -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Critères d\'évaluation') }}
                    </h3>
                    <div class="space-y-4">
                        @foreach($evaluation->responses as $response)
                            <div class="border dark:border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">
                                            {{ $response->criterion->name }}
                                        </h4>
                                        @if($response->comment)
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $response->comment }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                            {{ $response->rating }}/5
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Conclusion -->
                @if($evaluation->conclusion)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Conclusion') }}
                        </h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $evaluation->conclusion }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('evaluations.edit', $evaluation) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Modifier') }}
                    </a>
                    <form action="{{ route('evaluations.destroy', $evaluation) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette évaluation ?') }}')">
                            {{ __('Supprimer') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection