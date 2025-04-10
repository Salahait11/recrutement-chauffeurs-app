{{-- resources/views/employees/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
         <div class="flex flex-wrap justify-between items-center gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Fiche Employé :') }} {{ $employee->user->name ?? 'N/A' }} (#{{ $employee->id }})
            </h2>
             {{-- Lien Retour à la liste (utilise le nom de route préfixé) --}}
             <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Retour à la liste') }}
             </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Bloc Info Employé --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                     {{-- Messages Flash --}}
                     @if(session('success')) <div class="mb-4 bg-green-100 border border-green-400 text-green-700 dark:text-green-200 dark:border-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div> @endif
                     @if(session('error')) <div class="mb-4 bg-red-100 border border-red-400 text-red-700 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div> @endif

                    <h3 class="text-lg font-medium border-b pb-2 mb-4 dark:border-gray-700">Informations Principales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2">
                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Nom Complet') }}</div>
                        <div class="md:col-span-2">{{ $employee->user->name ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Email (Login)') }}</div>
                        <div class="md:col-span-2">{{ $employee->user->email ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Matricule') }}</div>
                        <div class="md:col-span-2 font-mono">{{ $employee->employee_number ?? '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Poste') }}</div>
                        <div class="md:col-span-2">{{ $employee->job_title ?? '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Département') }}</div>
                        <div class="md:col-span-2">{{ $employee->department ?? '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Manager') }}</div>
                        <div class="md:col-span-2">{{ $employee->manager->name ?? '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Date d\'Embauche') }}</div>
                        <div class="md:col-span-2">{{ optional($employee->hire_date)->format('d/m/Y') ?? '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Statut Actuel') }}</div>
                        <div class="md:col-span-2">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($employee->status == 'active') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($employee->status == 'terminated') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif"> {{-- on_leave --}}
                                {{ ucfirst($employee->status ?? '-') }}
                             </span>
                             @if($employee->status == 'terminated')
                               <span class="text-xs text-gray-500 dark:text-gray-400"> (depuis le {{ optional($employee->termination_date)->format('d/m/Y') ?? 'N/D' }})</span>
                             @endif
                        </div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Profil Candidat lié') }}</div>
                        <div class="md:col-span-2">
                            @if($employee->candidate)
                                <a href="{{ route('candidates.show', $employee->candidate_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">Voir profil recrutement #{{ $employee->candidate_id }}</a>
                            @else
                                <span class="text-sm italic text-gray-500">Aucun (création manuelle)</span>
                            @endif
                        </div>
                    </div>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}


            {{-- Bloc Infos Administratives --}}
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                     <h3 class="text-lg font-medium border-b pb-2 mb-4 dark:border-gray-700">Informations Administratives (Accès Restreint)</h3>
                     {{-- !! Logique d'autorisation pour masquer/afficher ce bloc !! --}}
                     {{-- @can('viewAdminData', $employee) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2">
                            <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('N° Sécurité Sociale') }}</div>
                            <div class="md:col-span-2">{{ $employee->social_security_number ?? '-' }}</div>

                            <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400 pt-1">{{ __('Coordonnées Bancaires') }}</div>
                            <div class="md:col-span-2 whitespace-pre-wrap text-sm">{{ $employee->bank_details ?? '-' }}</div>
                        </div>
                     {{-- @endcan --}}
                </div>
            </div>

             {{-- Bloc Actions --}}
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                   <h3 class="text-lg font-medium border-b pb-2 mb-4 dark:border-gray-700">Actions</h3>
                   <div class="flex flex-wrap justify-end items-center gap-3">

                        {{-- Bouton Exporter PDF --}}
                        <a href="{{ route('employees.pdf', $employee->id) }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            Exporter PDF
                        </a>

                        {{-- Bouton Modifier (lien préfixé car dans section admin) --}}
                        <a href="{{ route('employees.edit', $employee->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Modifier') }}
                        </a>

                        {{-- Bouton Terminer Contrat (redirige vers Edit avec statut pré-sélectionné?) ou Action dédiée ? --}}
                        @if($employee->status == 'active' || $employee->status == 'on_leave')
                        <a href="{{ route('employees.edit', ['employee' => $employee->id, 'set_status' => 'terminated']) }}" {{-- Paramètre optionnel --}}
                           class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-400 active:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Terminer Contrat
                        </a>
                        @endif

                        {{-- Bouton Supprimer (si autorisé et pertinent) --}}
                        {{-- <form method="POST" action="{{ route('employees.destroy', $employee->id) }}" onsubmit="return confirm('Supprimer cet employé et son utilisateur associé ? ATTENTION !');" class="inline"> @csrf @method('DELETE') <button type="submit" class="inline-flex items-center ... bg-red-600 ..."> Supprimer </button> </form> --}}
                   </div>
                </div>
            </div>


             {{-- Ajouter ici d'autres blocs : Demandes de congé de l'employé, Absences, Documents Employé, etc. --}}

        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>