{{-- resources/views/employees/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier Fiche Employé :') }} {{ $employee->user->name ?? 'N/A' }} (#{{ $employee->id }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Afficher les erreurs de validation --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oups!</strong>
                            <span class="block sm:inline">Il y a eu des problèmes avec votre saisie.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Section Informations Principales --}}
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2 mb-6">
                            Informations Principales
                        </h3>
                        <div class="space-y-6">
                            {{-- Nom Complet (Utilisateur) --}}
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nom Complet') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('name', $employee->user->name) }}" required>
                                @error('name') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email (Login) --}}
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email (Login)') }} <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('email', $employee->user->email) }}" required>
                                 @error('email') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Mot de passe (Optionnel - Laisser vide pour ne pas changer) --}}
                            {{--
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nouveau Mot de Passe') }}</label>
                                    <input type="password" name="password" id="password" class="block mt-1 w-full rounded-md ...">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Laisser vide pour conserver l'actuel.</p>
                                     @error('password') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Confirmer Nouveau Mot de Passe') }}</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="block mt-1 w-full rounded-md ...">
                                </div>
                            </div>
                             --}}

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Matricule Employé --}}
                                <div>
                                    <label for="employee_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Matricule Employé') }}</label>
                                    <input type="text" name="employee_number" id="employee_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('employee_number', $employee->employee_number) }}">
                                     @error('employee_number') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                                {{-- Poste --}}
                                <div>
                                    <label for="job_title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Poste') }}</label>
                                    <input type="text" name="job_title" id="job_title" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('job_title', $employee->job_title) }}">
                                     @error('job_title') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                 {{-- Département --}}
                                <div>
                                    <label for="department" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Département') }}</label>
                                    <input type="text" name="department" id="department" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('department', $employee->department) }}">
                                     @error('department') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                                 {{-- Manager --}}
                                <div>
                                    <label for="manager_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Manager Direct') }}</label>
                                    <select name="manager_id" id="manager_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                        <option value="">-- Aucun Manager --</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" {{ old('manager_id', $employee->manager_id) == $manager->id ? 'selected' : '' }}>
                                                {{ $manager->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                     @error('manager_id') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                             {{-- Date Embauche --}}
                            <div>
                                <label for="hire_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date d\'Embauche') }} <span class="text-red-500">*</span></label>
                                <input type="date" name="hire_date" id="hire_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('hire_date', optional($employee->hire_date)->format('Y-m-d')) }}" required>
                                @error('hire_date') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                             {{-- Statut --}}
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Statut Employé') }} <span class="text-red-500">*</span></label>
                                <select name="status" id="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="on_leave" {{ old('status', $employee->status) == 'on_leave' ? 'selected' : '' }}>En Congé Longue Durée</option>
                                    <option value="terminated" {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }}>Terminé</option>
                                </select>
                                @error('status') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Date de fin (conditionnelle) --}}
                            {{-- La classe 'hidden' sera gérée par le script JS --}}
                             <div id="termination_date_field" class="{{ old('status', $employee->status) == 'terminated' ? '' : 'hidden' }}">
                                <label for="termination_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date de Fin de Contrat (si statut = Terminé)') }}</label>
                                <input type="date" name="termination_date" id="termination_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('termination_date', optional($employee->termination_date)->format('Y-m-d')) }}">
                                @error('termination_date') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div> {{-- Fin space-y-6 Informations Principales --}}


                        {{-- Section Infos Administratives --}}
                        <hr class="dark:border-gray-700 my-8">
                        {{-- !! Ajouter une vérification de permissions ici (@can('editSensitiveEmployeeData', $employee)) --}}
                         <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2 mb-6">
                            Informations Administratives (Accès Restreint)
                        </h3>
                         <div class="space-y-6">
                             {{-- N° Sécu --}}
                             <div>
                                <label for="social_security_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('N° Sécurité Sociale') }}</label>
                                <input type="text" name="social_security_number" id="social_security_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('social_security_number', $employee->social_security_number) }}">
                                 @error('social_security_number') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                              {{-- Coordonnées Bancaires --}}
                             <div>
                                <label for="bank_details" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Coordonnées Bancaires') }}</label>
                                <textarea name="bank_details" id="bank_details" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('bank_details', $employee->bank_details) }}</textarea>
                                 @error('bank_details') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                         </div>
                         {{-- @endcan --}}


                        {{-- Boutons Annuler et Mettre à Jour --}}
                        <div class="flex items-center justify-end mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                             <a href="{{ route('employees.show', $employee->id) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Annuler') }}
                             </a>
                             <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Mettre à Jour Employé') }}
                             </button>
                         </div>
                    </form>

                    {{-- Script simple pour afficher/cacher la date de fin --}}
                    <script>
                        document.getElementById('status').addEventListener('change', function() {
                            var terminationField = document.getElementById('termination_date_field');
                            if (this.value === 'terminated') {
                                terminationField.classList.remove('hidden');
                            } else {
                                terminationField.classList.add('hidden');
                                // Optionnel: vider le champ si on change le statut vers non-terminé
                                // document.getElementById('termination_date').value = '';
                            }
                        });
                        // Exécuter au chargement pour l'état initial
                        document.addEventListener('DOMContentLoaded', function() {
                             var statusSelect = document.getElementById('status');
                             var terminationField = document.getElementById('termination_date_field');
                             if (statusSelect.value !== 'terminated') {
                                 terminationField.classList.add('hidden');
                             }
                        });
                    </script>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>