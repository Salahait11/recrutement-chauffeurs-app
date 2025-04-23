@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
        <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300">
                    {{ __('Détails de l\'Entretien') }}
            </h2>
                <div class="flex space-x-4">
                    <a href="{{ route('interviews.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Retour à la liste') }}
                    </a>
                    @if($interview->status !== 'terminé' && $interview->status !== 'annulé')
                        <a href="{{ route('interviews.edit', $interview) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ __('Modifier') }}
                    </a>
                @endif
                </div>
            </div>
        </div>

        <!-- Message de succès -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Contenu principal -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                <!-- Informations du candidat -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Informations du candidat') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Candidat') }}</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                            </p>
                                    </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Type d\'entretien') }}</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                @switch($interview->type)
                                    @case('initial')
                                        {{ __('Entretien initial') }}
                                        @break
                                    @case('technique')
                                        {{ __('Entretien technique') }}
                                        @break
                                    @case('final')
                                        {{ __('Entretien final') }}
                                        @break
                                @endswitch
                            </p>
                                    </div>
                                    </div>
                            </div>

                <!-- Détails de l'entretien -->
                <div class="border-b border-gray-200 dark:border-gray-700 py-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Détails de l\'entretien') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date et heure') }}</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $interview->interview_date->format('d/m/Y H:i') }}
                            </p>
                                    </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Interviewer') }}</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $interview->interviewer->name }}
                            </p>
                                    </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Statut') }}</h4>
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($interview->status === 'planifié') bg-yellow-100 text-yellow-800
                                    @elseif($interview->status === 'en cours') bg-blue-100 text-blue-800
                                    @elseif($interview->status === 'terminé') bg-green-100 text-green-800
                                    @elseif($interview->status === 'annulé') bg-red-100 text-red-800
                                    @elseif($interview->status === 'évalué') bg-purple-100 text-purple-800
                                    @endif">
                                                {{ ucfirst($interview->status) }}
                                            </span>
                            </div>
                        </div>
                                </div>
                            </div>

                <!-- Notes -->
                @if($interview->notes)
                    <div class="py-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Notes') }}
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            {{ $interview->notes }}
                                    </div>
                                </div>
                            @endif

                <!-- Actions -->
                <div class="mt-6 flex justify-end space-x-4">
                    @if($interview->status !== 'terminé' && $interview->status !== 'évalué' && $interview->status !== 'annulé')
                        <a href="{{ route('interviews.edit', $interview) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Modifier') }}
                        </a>
                    @endif

                    @if($interview->status === 'planifié')
                        <form action="{{ route('interviews.start', $interview) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Démarrer l\'entretien') }}
                            </button>
                        </form>
                    @endif

                    @if($interview->status === 'en cours')
                        <form action="{{ route('interviews.complete', $interview) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Terminer l\'entretien') }}
                            </button>
                        </form>
                    @endif

                    @if($interview->status === 'terminé' && !$interview->evaluation)
                        <a href="{{ route('interviews.evaluations.create', $interview) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Créer l\'évaluation') }}
                        </a>
                    @endif

                    @if($interview->evaluation)
                        <a href="{{ route('evaluations.show', $interview->evaluation) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Voir l\'évaluation') }}
                        </a>
                    @endif

                    @if($interview->status !== 'annulé' && $interview->status !== 'évalué')
                        <form action="{{ route('interviews.cancel', $interview) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Annuler l\'entretien') }}
                            </button>
                        </form>
                                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection