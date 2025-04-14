{{-- resources/views/candidates/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ajouter un Candidat') }}
            </h2>
            <a href="{{ route('candidates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 dark:bg-red-800/20 dark:border-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Erreur!</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('candidates.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Prénom -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" id="first_name" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('first_name') }}" required>
                            </div>

                            <!-- Nom -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" id="last_name" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('last_name') }}" required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('email') }}" required>
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Téléphone <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="phone" id="phone" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('phone') }}" required>
                            </div>

                            <!-- Date de naissance -->
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Date de naissance <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="birth_date" id="birth_date" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('birth_date') }}" required>
                            </div>

                            <!-- Années d'expérience -->
                            <div>
                                <label for="years_of_experience" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Années d'expérience <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="years_of_experience" id="years_of_experience" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('years_of_experience') }}" required>
                            </div>

                            <!-- Numéro de permis -->
                            <div>
                                <label for="driving_license_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Numéro de permis de conduire <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="driving_license_number" id="driving_license_number" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('driving_license_number') }}" required>
                            </div>

                            <!-- Date d'expiration du permis -->
                            <div>
                                <label for="driving_license_expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Date d'expiration du permis <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="driving_license_expiry" id="driving_license_expiry" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('driving_license_expiry') }}" required>
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Adresse <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="address" id="address" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('address') }}" required>
                        </div>

                        <!-- Statut (caché) -->
                        <input type="hidden" name="status" id="status" value="{{ \App\Models\Candidate::STATUS_NOUVEAU }}">
                        
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Le statut initial du candidat sera automatiquement défini comme "{{ \App\Models\Candidate::$statuses[\App\Models\Candidate::STATUS_NOUVEAU] }}".
                                </span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Notes
                            </label>
                            <textarea name="notes" id="notes" rows="4" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Boutons -->
                        <div class="flex justify-between pt-4">
                            <button type="reset" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Réinitialiser
                            </button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Définir la date minimale pour l'expiration du permis (aujourd'hui + 1 jour)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
            
            document.getElementById('driving_license_expiry').setAttribute('min', tomorrowFormatted);
        });
    </script>
</x-app-layout>