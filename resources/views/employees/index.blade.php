{{-- resources/views/employees/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec titre et bouton d'ajout -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône Briefcase (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.07a2.25 2.25 0 0 1-2.25 2.25h-12a2.25 2.25 0 0 1-2.25-2.25v-4.07a2.25 2.25 0 0 1 2.25-2.25h12a2.25 2.25 0 0 1 2.25 2.25Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75Zm0 0v-3.75a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v3.75m-4.5 0v3.75a.75.75 0 0 0 .75.75h3a.75.75 0 0 0 .75-.75V12.75m-4.5 0h4.5m-4.5 0a.75.75 0 0 1-.75-.75v-1.5a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-.75.75m-1.5 0a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 .75.75h1.5a.75.75 0 0 0 .75-.75v-1.5a.75.75 0 0 0-.75-.75M12 12.75v-1.5m0 1.5v1.5m0 0v1.5m0-1.5a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1-.75-.75v-1.5a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75m-7.5-3.75h10.5a2.25 2.25 0 0 0-2.25-2.25h-6a2.25 2.25 0 0 0-2.25 2.25Z" />
                            </svg>
                            Liste des Employés
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Ajouter Employé
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Message de succès -->
    @if (session('success'))
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
    <!-- Message d'erreur -->
    @if (session('error'))
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

    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            {{-- Formulaire unique pour tous les filtres et la recherche --}}
            <form method="GET" action="{{ route('employees.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Champ Recherche --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Recherche</label>
                        <input type="text" name="search" id="search"
                               value="{{ request('search') }}"
                               placeholder="Nom, email, matricule..."
                               class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    </div>

                    {{-- Filtre Statut --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Statut</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">Tous</option>
                            @foreach($statuses as $statusValue => $statusLabel)
                                <option value="{{ $statusValue }}" {{ request('status') == $statusValue ? 'selected' : '' }}>
                                    {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtre Poste --}}
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Poste</label>
                        <select name="job_title" id="job_title" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">Tous</option>
                            @foreach($jobTitles as $title)
                                <option value="{{ $title }}" {{ request('job_title') == $title ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Boutons d'action --}}
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Filtrer
                        </button>
                        @if(request()->hasAny(['search', 'status', 'job_title']))
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Réinitialiser
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Employés -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                             {{-- Helper function for sort icon --}}
                            @php
                            $sortIcon = function($field) {
                                if (request('sort') == $field) {
                                    return request('direction', 'asc') == 'asc' ?
                                        '<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>' :
                                        '<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>';
                                }
                                return '<svg class="invisible h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>';
                            };
                            @endphp
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => (request('sort') == 'name' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Nom
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'name' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                        {!! $sortIcon('name') !!}
                                    </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'employee_number', 'direction' => (request('sort') == 'employee_number' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Matricule
                                     <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'employee_number' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                        {!! $sortIcon('employee_number') !!}
                                    </span>
                                </a>
                            </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'job_title', 'direction' => (request('sort') == 'job_title' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Poste
                                     <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'job_title' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                         {!! $sortIcon('job_title') !!}
                                    </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'hire_date', 'direction' => (request('sort') == 'hire_date' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Date Embauche
                                     <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'hire_date' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                         {!! $sortIcon('hire_date') !!}
                                    </span>
                                </a>
                            </th>
                           <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => (request('sort') == 'status' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Statut
                                     <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'status' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                         {!! $sortIcon('status') !!}
                                    </span>
                                </a>
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                {{-- Colonne Nom avec Avatar/Initiales --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            {{-- Adapter la logique photo/initiales à votre modèle User/Employee --}}
                                            @if($employee->user && $employee->user->profile_photo_path)
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $employee->user->profile_photo_path) }}" alt="{{ $employee->user->name ?? '' }}">
                                            @elseif($employee->user)
                                                @php
                                                    // Simple logic for initials - adapt as needed
                                                    $nameParts = explode(' ', $employee->user->name ?? 'N A');
                                                    $initials = count($nameParts) >= 2 ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1)) : strtoupper(substr($employee->user->name ?? 'NA', 0, 1));
                                                @endphp
                                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                    <span class="text-blue-600 dark:text-blue-300 font-medium">{{ $initials }}</span>
                                                </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                    <span class="text-gray-500 dark:text-gray-400">?</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                 <a href="{{ route('employees.show', $employee->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ $employee->user->name ?? 'N/A' }}</a>
                                            </div>
                                             {{-- Optional: email under name --}}
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $employee->employee_number ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $employee->job_title ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $employee->hire_date ? $employee->hire_date->format('d/m/Y') : '-' }}
                                </td>
                                {{-- Colonne Statut avec Badge --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        // Assuming $statuses contains [value => label]
                                        $statusLabel = $statuses[$employee->status] ?? ucfirst($employee->status);
                                        $statusClass = '';
                                        switch ($employee->status) {
                                            case 'active':
                                                $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                                break;
                                            case 'terminated':
                                                $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                                break;
                                            case 'on_leave': // Example for another status
                                                $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                                break;
                                            default:
                                                $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                        }
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                {{-- Colonne Actions avec Icônes --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('employees.show', $employee->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Voir">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="Modifier">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                         {{-- Suppression gérée avec précaution --}}
                                         {{-- <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Supprimer">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                    Aucun employé trouvé
                                    {{-- Lien Réinitialiser omis pour coller au modèle --}}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination placée ici comme dans le modèle --}}
            <div class="mt-4">
                {{ $employees->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection