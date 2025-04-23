{{-- resources/views/employees/show.blade.php --}}
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

    <!-- En-tête avec titre et actions principales -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône User/ID Card (Exemple) --}}
                             <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.025A6.721 6.721 0 0 1 6.75 15.75h1.75a.75.75 0 0 1 .75.75V18a.75.75 0 0 1-.75.75h-1.75a6.75 6.75 0 0 1 0-2.25Z" />
                             </svg>
                            Fiche Employé
                        </div>
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                       {{ $employee->user->name ?? 'N/A' }} (#{{ $employee->employee_number ?? $employee->id }})
                    </p>
                </div>
                <div class="mt-4 flex flex-shrink-0 md:ml-4 md:mt-0 space-x-3">
                    <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la liste
                    </a>
                    <a href="{{ route('employees.edit', $employee->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
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

    <!-- Bloc Principal d'Informations -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6 space-y-6">
            <!-- Informations Principales -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations Principales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom Complet</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->user->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email (Login)</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->user->email ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Matricule</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $employee->employee_number ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poste</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->job_title ?? '-' }}</p>
                    </div>

                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Département</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->department ?? '-' }}</p>
                    </div>

                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Manager</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->manager->name ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date d'Embauche</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ optional($employee->hire_date)->format('d/m/Y') ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut Actuel</label>
                        <p class="mt-1">
                            @php
                                $statusLabel = ucfirst($employee->status ?? '-');
                                $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'; // Default
                                if ($employee->status == 'active') {
                                    $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                    $statusLabel = 'Actif';
                                } elseif ($employee->status == 'terminated') {
                                    $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                    $statusLabel = 'Terminé';
                                } elseif ($employee->status == 'on_leave') {
                                     $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                     $statusLabel = 'En Congé';
                                }
                            @endphp
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                             </span>
                             @if($employee->status == 'terminated')
                               <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">(depuis le {{ optional($employee->termination_date)->format('d/m/Y') ?? 'N/D' }})</span>
                             @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profil Candidat lié</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            @if($employee->candidate)
                                <a href="{{ route('candidates.show', $employee->candidate_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    Voir profil recrutement #{{ $employee->candidate_id }}
                                </a>
                            @else
                                <span class="italic text-gray-500 dark:text-gray-400">Aucun (création manuelle)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Informations Administratives (Optionnel et conditionnel) --}}
            {{-- @can('viewAdminData', $employee) --}}
            <hr class="dark:border-gray-700">
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations Administratives</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">N° Sécurité Sociale</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->social_security_number ?? '-' }}</p>
                    </div>
                     <div></div> {{-- Placeholder for alignment --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coordonnées Bancaires</label>
                         <div class="mt-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $employee->bank_details ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
             {{-- @endcan --}}

        </div>
    </div>

    <!-- Bloc Actions Opérationnelles -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300 mb-4 border-b dark:border-gray-700 pb-2">Actions Supplémentaires</h3>
            <div class="flex flex-wrap justify-end items-center gap-3">
                {{-- Bouton Exporter PDF --}}
                <a href="{{ route('employees.pdf', $employee->id) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    Exporter PDF
                </a>

                {{-- Bouton Terminer Contrat --}}
                @if($employee->status == 'active' || $employee->status == 'on_leave')
                <a href="{{ route('employees.edit', ['employee' => $employee->id, 'intent' => 'terminate']) }}" {{-- Paramètre optionnel pour pré-remplir le formulaire --}}
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                     <svg class="-ml-0.5 mr-1.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2 10a.75.75 0 0 1 .75-.75h12.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M12.28 15.22a.75.75 0 0 1 0-1.06l2.25-2.25a.75.75 0 0 1 1.06 1.06l-3 3a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 1 1 1.06-1.06l2.25 2.25Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M12.28 4.78a.75.75 0 0 1 0 1.06l-2.25 2.25a.75.75 0 0 1-1.06-1.06l3-3a.75.75 0 0 1 1.06 0l3 3a.75.75 0 1 1-1.06 1.06l-2.25-2.25Z" clip-rule="evenodd" />
                    </svg>
                    Terminer Contrat
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Ajouter ici d'autres blocs futurs (congés, etc.) avec la même structure --}}

</div>
@endsection