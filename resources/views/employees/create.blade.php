{{-- resources/views/employees/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajouter un Nouvel Employé') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Affichage des erreurs --}}
                     @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oups!</strong>
                            <span class="block sm:inline">Veuillez corriger les erreurs ci-dessous.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                     @if (session('error'))
                         <div class="mb-6 bg-red-100 ..." role="alert">{{ session('error') }}</div>
                     @endif


                    {{-- Formulaire de création --}}
                    <form method="POST" action="{{ route('employees.store') }}"> {{-- Route standard --}}
                        @csrf

                        {{-- Section Informations Utilisateur --}}
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2 mb-6">
                            Informations de Connexion (Utilisateur)
                        </h3>
                         <div class="space-y-6 mb-8">
                             {{-- Nom Complet --}}
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nom Complet de l\'employé') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('name') }}" required autofocus>
                                @error('name') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email --}}
                             <div>
                                <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email de l\'employé') }} <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('email') }}" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Doit être unique. Un mot de passe aléatoire sera généré.</p>
                                 @error('email') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                             {{-- Note: Pas de champ mot de passe, il est généré aléatoirement --}}
                         </div>


                        {{-- Section Informations Employé --}}
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2 mb-6">
                            Informations Professionnelles
                        </h3>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Matricule Employé --}}
                                <div>
                                    <label for="employee_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Matricule Employé (Optionnel)') }}</label>
                                    <input type="text" name="employee_number" id="employee_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('employee_number') }}">
                                     @error('employee_number') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                                {{-- Poste --}}
                                <div>
                                    <label for="job_title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Poste') }}</label>
                                    <input type="text" name="job_title" id="job_title" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('job_title') }}">
                                     @error('job_title') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                 {{-- Département --}}
                                <div>
                                    <label for="department" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Département') }}</label>
                                    <input type="text" name="department" id="department" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('department') }}">
                                     @error('department') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                                 {{-- Manager --}}
                                <div>
                                    <label for="manager_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Manager Direct (Optionnel)') }}</label>
                                    <select name="manager_id" id="manager_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Aucun Manager --</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
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
                                <input type="date" name="hire_date" id="hire_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('hire_date', date('Y-m-d')) }}" required>
                                @error('hire_date') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Statut initial --}}
                            {{-- Caché car on force 'active' dans le contrôleur store, mais on pourrait le rendre modifiable --}}
                            <input type="hidden" name="status" value="active">
                            {{--
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Statut Initial') }}</label>
                                <select name="status" id="status" class="block mt-1 w-full rounded-md ..." required>
                                    <option value="active" selected>Actif</option>
                                    <option value="on_leave">En Congé Longue Durée</option>
                                    <option value="terminated">Terminé</option>
                                </select>
                            </div>
                            --}}

                        </div> {{-- Fin space-y-6 Informations Pro --}}


                         {{-- Section Infos Administratives (Optionnel) --}}
                         <hr class="dark:border-gray-700 my-8">
                         <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2 mb-6">
                             Informations Administratives (Optionnel)
                         </h3>
                          <div class="space-y-6">
                              {{-- N° Sécu --}}
                              <div>
                                 <label for="social_security_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('N° Sécurité Sociale') }}</label>
                                 <input type="text" name="social_security_number" id="social_security_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('social_security_number') }}">
                                  @error('social_security_number') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                             </div>
                               {{-- Coordonnées Bancaires --}}
                              <div>
                                 <label for="bank_details" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Coordonnées Bancaires') }}</label>
                                 <textarea name="bank_details" id="bank_details" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('bank_details') }}</textarea>
                                  @error('bank_details') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                             </div>
                          </div>


                        {{-- Boutons --}}
                        <div class="flex items-center justify-end mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                             {{-- Lien Annuler standard --}}
                            <a href="{{ route('employees.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Créer Employé') }}
                            </button>
                         </div>
                    </form>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>