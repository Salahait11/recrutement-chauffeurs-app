{{-- resources/views/employees/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Fiche Employé :') }} {{ $employee->user->name ?? 'N/A' }}
            </h2>
            <a href="{{ route('employees.index') }}" class="inline-flex items-center ...">{{ __('Retour à la liste') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Bloc Info Employé --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                     @if(session('success')) <div class="mb-4 bg-green-100 ...">{{ session('success') }}</div> @endif

                    <h3 class="text-lg font-medium border-b pb-2 mb-4">Informations Principales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2">
                        <div class="font-semibold ...">{{ __('Nom Complet') }}</div><div>{{ $employee->user->name ?? 'N/A' }}</div><div></div>{{-- Colonne vide pour alignement --}}
                        <div class="font-semibold ...">{{ __('Email (Login)') }}</div><div>{{ $employee->user->email ?? 'N/A' }}</div><div></div>
                        <div class="font-semibold ...">{{ __('Matricule') }}</div><div>{{ $employee->employee_number ?? 'Non défini' }}</div><div></div>
                        <div class="font-semibold ...">{{ __('Poste') }}</div><div>{{ $employee->job_title ?? 'Non défini' }}</div><div></div>
                        <div class="font-semibold ...">{{ __('Département') }}</div><div>{{ $employee->department ?? 'Non défini' }}</div><div></div>
                        <div class="font-semibold ...">{{ __('Manager') }}</div><div>{{ $employee->manager->name ?? 'N/A' }}</div><div></div>
                        <div class="font-semibold ...">{{ __('Date d\'Embauche') }}</div><div>{{ $employee->hire_date->format('d/m/Y') }}</div><div></div>
                        <div class="font-semibold ...">{{ __('Statut Actuel') }}</div>
                        <div>
                             <span class="px-2 inline-flex text-xs ... rounded-full @if($employee->status == 'active') ... @endif">
                                {{ ucfirst($employee->status) }}
                             </span>
                             @if($employee->status == 'terminated') ({{ optional($employee->termination_date)->format('d/m/Y') }}) @endif
                        </div>
                        <div></div>
                         <div class="font-semibold ...">{{ __('Profil Candidat lié') }}</div>
                        <div>
                            @if($employee->candidate)
                                <a href="{{ route('candidates.show', $employee->candidate_id) }}" class="text-blue-600 ...">Voir le profil candidat #{{ $employee->candidate_id }}</a>
                            @else
                                Non lié ou candidat supprimé
                            @endif
                        </div>
                        <div></div>
                    </div>

                     <hr class="dark:border-gray-600 mt-6">
                     {{-- Actions --}}
                     <div class="flex justify-end space-x-3 mt-6">
                         <a href="{{ route('employees.edit', $employee->id) }}" class="inline-flex items-center ...">{{ __('Modifier') }}</a>
                         {{-- Bouton pour changer statut / Terminer contrat ? --}}
                     </div>
                </div>
            </div>

            {{-- Bloc Infos Administratives (Afficher avec prudence !) --}}
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                     <h3 class="text-lg font-medium border-b pb-2 mb-4">Informations Administratives (Accès Restreint)</h3>
                     {{-- !! Ajouter une vérification des permissions ici pour n'afficher qu'aux RH/Admins !! --}}
                     {{-- @can('viewSensitiveEmployeeData', $employee) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2">
                            <div class="font-semibold ...">{{ __('N° Sécurité Sociale') }}</div><div>{{ $employee->social_security_number ?? 'Non renseigné' }}</div><div></div>
                            <div class="font-semibold ... pt-1">{{ __('Coordonnées Bancaires') }}</div><div class="whitespace-pre-wrap text-sm">{{ $employee->bank_details ?? 'Non renseignées' }}</div><div></div>
                        </div>
                     {{-- @endcan --}}
                </div>
            </div>

             {{-- Ajouter ici bloc pour Documents Employé, Congés, etc. --}}

        </div>
    </div>
</x-app-layout>