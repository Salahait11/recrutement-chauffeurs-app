{{-- resources/views/employees/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Messages Flash -->
    {{-- Les messages flash seront typiquement affichés sur la page de redirection (index) ou via le layout principal si configuré --}}
    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800">
            {{-- ... contenu du message succès ... --}}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800">
             {{-- ... contenu du message erreur ... --}}
             <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- En-tête avec titre -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                             <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                             </svg>
                            Ajouter un Nouvel Employé
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Informations Utilisateur -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations de Connexion (Utilisateur)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Nom Complet --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom Complet de l'employé <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email de l'employé <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Doit être unique. Un mot de passe aléatoire sera généré et devra être réinitialisé par l'utilisateur.</p>
                         @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="dark:border-gray-700 my-4">

            <!-- Informations Employé -->
            <div class="space-y-4">
                 <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations Professionnelles</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Matricule --}}
                    <div>
                        <label for="employee_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Matricule Employé</label>
                        <input type="text" name="employee_number" id="employee_number" value="{{ old('employee_number') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                         @error('employee_number') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Poste --}}
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poste</label>
                        <input type="text" name="job_title" id="job_title" value="{{ old('job_title') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                         @error('job_title') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Département --}}
                     <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Département</label>
                        <input type="text" name="department" id="department" value="{{ old('department') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                         @error('department') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Manager --}}
                    <div>
                        <label for="manager_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Manager Direct</label>
                        <select id="manager_id" name="manager_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">-- Aucun Manager --</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" @selected(old('manager_id') == $manager->id)>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                         @error('manager_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                     {{-- Date d'embauche --}}
                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date d'embauche <span class="text-red-500">*</span></label>
                        <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('hire_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Statut initial (caché, défini comme 'active') --}}
                    <input type="hidden" name="status" value="active">

                 </div>
            </div>

            {{-- Informations Administratives (Optionnel - Accès Restreint) --}}
            {{-- @can('editAdminData', App\Models\Employee::class) --}}
            <hr class="dark:border-gray-700 my-4">
             <div class="space-y-4">
                 <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations Administratives (Optionnel)</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     {{-- N° Sécurité Sociale --}}
                    <div>
                        <label for="social_security_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">N° Sécurité Sociale</label>
                        <input type="text" name="social_security_number" id="social_security_number" value="{{ old('social_security_number') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('social_security_number') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div></div> {{-- Placeholder --}}

                    {{-- Coordonnées Bancaires --}}
                    <div class="md:col-span-2">
                        <label for="bank_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coordonnées Bancaires</label>
                        <textarea name="bank_details" id="bank_details" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm"
                                  >{{ old('bank_details') }}</textarea>
                         @error('bank_details') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                 </div>
             </div>
            {{-- @endcan --}}


            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    Créer Employé
                </button>
            </div>
        </form>
    </div>
</div>
@endsection