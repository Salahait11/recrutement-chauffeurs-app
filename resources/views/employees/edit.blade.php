{{-- resources/views/employees/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Messages Flash -->
    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800">
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
                             <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Modifier Fiche Employé
                        </div>
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                       {{ $employee->user->name ?? 'N/A' }} (#{{ $employee->employee_number ?? $employee->id }})
                    </p>
                </div>
                 <div class="mt-4 flex flex-shrink-0 md:ml-4 md:mt-0 space-x-3">
                    <a href="{{ route('employees.show', $employee->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la fiche
                    </a>
                     {{-- Bouton Supprimer (si nécessaire et autorisé) --}}
                    {{-- <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet employé et son utilisateur associé ? ATTENTION !');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer
                        </button>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Informations Principales -->
            <div class="space-y-4">
                 <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations Principales</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     {{-- Nom Complet (Utilisateur) --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom Complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $employee->user->name) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email (Login) --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email (Login) <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $employee->user->email) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Matricule Employé --}}
                    <div>
                        <label for="employee_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Matricule Employé</label>
                        <input type="text" name="employee_number" id="employee_number" value="{{ old('employee_number', $employee->employee_number) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('employee_number') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Poste --}}
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poste</label>
                        <input type="text" name="job_title" id="job_title" value="{{ old('job_title', $employee->job_title) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('job_title') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Département --}}
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Département</label>
                        <input type="text" name="department" id="department" value="{{ old('department', $employee->department) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                         @error('department') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                     {{-- Manager --}}
                    <div>
                        <label for="manager_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Manager Direct</label>
                        <select name="manager_id" id="manager_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">-- Aucun Manager --</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" @selected(old('manager_id', $employee->manager_id) == $manager->id)>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('manager_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Date Embauche --}}
                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date d'Embauche <span class="text-red-500">*</span></label>
                        <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', optional($employee->hire_date)->format('Y-m-d')) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('hire_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Statut --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut Employé <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            {{-- Assuming $statuses is passed as [value => label] --}}
                             @foreach($statuses as $statusValue => $statusLabel)
                                <option value="{{ $statusValue }}" @selected(old('status', $employee->status) == $statusValue)>
                                    {{ $statusLabel }}
                                </option>
                             @endforeach
                        </select>
                         @error('status') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Date de fin (conditionnelle) --}}
                     <div id="termination_date_field" class="{{ old('status', $employee->status) == 'terminated' ? '' : 'hidden' }}">
                        <label for="termination_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Fin de Contrat</label>
                        <input type="date" name="termination_date" id="termination_date" value="{{ old('termination_date', optional($employee->termination_date)->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Requis si le statut est 'Terminé'.</p>
                        @error('termination_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lien Candidat (Affichage seulement, non modifiable ici en général) --}}
                    @if($employee->candidate)
                     <div class="md:col-span-2">
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profil Candidat lié</label>
                         <p class="mt-1 text-sm">
                            <a href="{{ route('candidates.show', $employee->candidate_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                Voir profil recrutement #{{ $employee->candidate_id }}
                            </a>
                         </p>
                    </div>
                    @endif

                 </div>
            </div>

             {{-- Section Mot de passe (Optionnelle, commentée par défaut) --}}
             {{-- <hr class="dark:border-gray-700 my-4">
             <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Modifier Mot de Passe (Optionnel)</h3>
                 <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Laissez les champs suivants vides pour conserver le mot de passe actuel.</p>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nouveau Mot de Passe</label>
                        <input type="password" name="password" id="password" autocomplete="new-password"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmer Nouveau Mot de Passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    </div>
                 </div>
             </div> --}}


            {{-- Informations Administratives (Accès Restreint - Exemple) --}}
            {{-- @can('editAdminData', $employee) --}}
            <hr class="dark:border-gray-700 my-4">
             <div class="space-y-4">
                 <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations Administratives</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     {{-- N° Sécurité Sociale --}}
                    <div>
                        <label for="social_security_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">N° Sécurité Sociale</label>
                        <input type="text" name="social_security_number" id="social_security_number" value="{{ old('social_security_number', $employee->social_security_number) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('social_security_number') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                     <div></div> {{-- Placeholder for alignment --}}

                     {{-- Coordonnées Bancaires --}}
                    <div class="md:col-span-2"> {{-- Prend toute la largeur --}}
                        <label for="bank_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coordonnées Bancaires</label>
                        <textarea name="bank_details" id="bank_details" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm"
                                  >{{ old('bank_details', $employee->bank_details) }}</textarea>
                         @error('bank_details') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                 </div>
             </div>
            {{-- @endcan --}}

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('employees.show', $employee->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script simple pour afficher/cacher la date de fin --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const terminationDateField = document.getElementById('termination_date_field');
        const terminationDateInput = document.getElementById('termination_date');

        function toggleTerminationDate() {
            if (statusSelect.value === 'terminated') {
                terminationDateField.classList.remove('hidden');
                // Rendre le champ requis quand il est visible (si la validation backend le permet)
                // terminationDateInput.required = true; 
            } else {
                terminationDateField.classList.add('hidden');
                // Optionnel: vider le champ et le rendre non requis
                // terminationDateInput.value = '';
                // terminationDateInput.required = false; 
            }
        }

        statusSelect.addEventListener('change', toggleTerminationDate);
        // Exécuter au chargement pour l'état initial
        toggleTerminationDate();
    });
</script>
@endsection