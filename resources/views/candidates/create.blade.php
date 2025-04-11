{{-- resources/views/candidates/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajouter un Nouveau Candidat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

                    {{-- Formulaire de création --}}
                    <form method="POST" action="{{ route('candidates.store') }}">
                        @csrf {{-- Protection CSRF obligatoire pour les formulaires POST --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Prénom --}}
                            <div>
                                <label for="first_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Prénom') }}</label>
                                <input type="text" name="first_name" id="first_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('first_name') }}" required autofocus>
                            </div>

                            {{-- Nom --}}
                            <div>
                                <label for="last_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nom') }}</label>
                                <input type="text" name="last_name" id="last_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('last_name') }}" required>
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                                <input type="email" name="email" id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('email') }}" required>
                            </div>

                            {{-- Téléphone --}}
                            <div>
                                <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Téléphone') }}</label>
                                <input type="tel" name="phone" id="phone" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('phone') }}" required>
                            </div>

                            {{-- Années d'expérience --}}
                            <div>
                                <label for="years_of_experience" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Années d\'expérience') }}</label>
                                <input type="number" name="years_of_experience" id="years_of_experience" min="0" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('years_of_experience') }}" required>
                            </div>

                            {{-- Adresse (champ texte multiligne) --}}
                            <div class="md:col-span-2">
                                <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Adresse') }}</label>
                                <textarea name="address" id="address" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>{{ old('address') }}</textarea>
                            </div>

                            {{-- Date de Naissance --}}
                            <div>
                                <label for="birth_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date de Naissance') }}</label>
                                <input type="date" name="birth_date" id="birth_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('birth_date') }}" required>
                            </div>

                            {{-- Numéro Permis --}}
                            <div>
                                <label for="driving_license_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Numéro Permis de Conduire') }}</label>
                                <input type="text" name="driving_license_number" id="driving_license_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('driving_license_number') }}" required>
                            </div>

                            {{-- Expiration Permis --}}
                            <div>
                                <label for="driving_license_expiry" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date Expiration Permis') }}</label>
                                <input type="date" name="driving_license_expiry" id="driving_license_expiry" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('driving_license_expiry') }}" required>
                            </div>

                            {{-- Notes (champ texte multiligne) --}}
                            <div class="md:col-span-2">
                                <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Notes') }}</label>
                                <textarea name="notes" id="notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        {{-- Bouton Soumettre --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('candidates.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>