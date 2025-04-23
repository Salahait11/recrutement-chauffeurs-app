{{-- resources/views/offers/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300">
                    {{ __('Modifier l\'Offre') }}
                </h2>
                <a href="{{ route('offers.show', $offer) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Retour') }}
                </a>
            </div>
        </div>

        <!-- Message d'erreur -->
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <strong class="font-bold">{{ __('Oups!') }}</strong>
                <span class="block sm:inline">{{ __('Il y a eu des problèmes avec votre saisie.') }}</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire de modification -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('offers.update', $offer) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Informations de base -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="position_offered" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Poste proposé') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="position_offered" id="position_offered" value="{{ old('position_offered', $offer->position_offered) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                        </div>

                        <div>
                            <label for="contract_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Type de contrat') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="contract_type" id="contract_type" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                <option value="CDI" {{ old('contract_type', $offer->contract_type) === 'CDI' ? 'selected' : '' }}>CDI</option>
                                <option value="CDD" {{ old('contract_type', $offer->contract_type) === 'CDD' ? 'selected' : '' }}>CDD</option>
                                <option value="Stage" {{ old('contract_type', $offer->contract_type) === 'Stage' ? 'selected' : '' }}>Stage</option>
                                <option value="Alternance" {{ old('contract_type', $offer->contract_type) === 'Alternance' ? 'selected' : '' }}>Alternance</option>
                            </select>
                        </div>

                        <div>
                            <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Salaire') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="salary" id="salary" value="{{ old('salary', $offer->salary) }}" step="0.01" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">€</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="salary_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Période de salaire') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="salary_period" id="salary_period" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                <option value="annuel" {{ old('salary_period', $offer->salary_period) === 'annuel' ? 'selected' : '' }}>Annuel</option>
                                <option value="mensuel" {{ old('salary_period', $offer->salary_period) === 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                                <option value="horaire" {{ old('salary_period', $offer->salary_period) === 'horaire' ? 'selected' : '' }}>Horaire</option>
                            </select>
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Date de début') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $offer->start_date->format('Y-m-d')) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                        </div>

                        <div>
                            <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Date d\'expiration') }}
                            </label>
                            <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', $offer->expires_at ? $offer->expires_at->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                        </div>
                    </div>

                    <!-- Avantages et conditions -->
                    <div class="space-y-6">
                        <div>
                            <label for="benefits" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Avantages') }}
                            </label>
                            <textarea name="benefits" id="benefits" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">{{ old('benefits', $offer->benefits) }}</textarea>
                        </div>

                        <div>
                            <label for="specific_conditions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Conditions spécifiques') }}
                            </label>
                            <textarea name="specific_conditions" id="specific_conditions" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">{{ old('specific_conditions', $offer->specific_conditions) }}</textarea>
                        </div>
                    </div>

                    <!-- Statut et actions -->
                    <div class="flex justify-end space-x-4">
                        <div class="mr-auto">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Statut') }}
                            </label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                <option value="{{ \App\Models\Offer::STATUS_BROUILLON }}" {{ old('status', $offer->status) === \App\Models\Offer::STATUS_BROUILLON ? 'selected' : '' }}>{{ __('Brouillon') }}</option>
                                <option value="{{ \App\Models\Offer::STATUS_ENVOYEE }}" {{ old('status', $offer->status) === \App\Models\Offer::STATUS_ENVOYEE ? 'selected' : '' }}>{{ __('Envoyée') }}</option>
                            </select>
                        </div>
                        <a href="{{ route('offers.show', $offer) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Annuler') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Enregistrer') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection