{{-- resources/views/offers/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300">
                    {{ __('Détails de l\'Offre') }}
                </h2>
                <div class="flex space-x-4">
                    <a href="{{ route('offers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Retour') }}
                    </a>
                    <a href="{{ route('offers.pdf', $offer) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ __('Télécharger PDF') }}
                    </a>
                    @if($offer->status === 'brouillon')
                        <a href="{{ route('offers.edit', $offer) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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

        <!-- Détails de l'offre -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations du candidat -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">
                            {{ __('Informations du Candidat') }}
                        </h3>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Nom') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $offer->candidate->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Téléphone') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $offer->candidate->phone }}</p>
                        </div>
                    </div>

                    <!-- Détails de l'offre -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">
                            {{ __('Détails de l\'Offre') }}
                        </h3>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Poste proposé') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $offer->position_offered }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Type de contrat') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $offer->contract_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Salaire') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ number_format($offer->salary, 2) }} € / {{ $offer->salary_period }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date de début') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $offer->start_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Statut') }}</p>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($offer->status === \App\Models\Offer::STATUS_BROUILLON) bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @elseif($offer->status === \App\Models\Offer::STATUS_ENVOYEE) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($offer->status === \App\Models\Offer::STATUS_ACCEPTEE) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($offer->status === \App\Models\Offer::STATUS_REFUSEE) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @elseif($offer->status === \App\Models\Offer::STATUS_EXPIREE) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    {{ __(ucfirst($offer->status)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Avantages et conditions -->
                <div class="mt-8 space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">
                        {{ __('Avantages et Conditions') }}
                    </h3>
                    @if($offer->benefits)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Avantages') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $offer->benefits }}</p>
                        </div>
                    @endif
                    @if($offer->specific_conditions)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Conditions spécifiques') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $offer->specific_conditions }}</p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                @if($offer->status === \App\Models\Offer::STATUS_ENVOYEE)
                    <div class="mt-8 flex justify-end space-x-4">
                        <form action="{{ route('offers.update-status', $offer) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="{{ \App\Models\Offer::STATUS_ACCEPTEE }}">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Accepter l\'offre') }}
                            </button>
                        </form>
                        <form action="{{ route('offers.update-status', $offer) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="{{ \App\Models\Offer::STATUS_REFUSEE }}">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Refuser l\'offre') }}
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection