{{-- resources/views/leave_requests/index.blade.php --}}
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
                            {{-- Icône Calendar (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0h18M-4.5 12h22.5" /> {{-- Simple Calendar Icon --}}
                            </svg>
                            @if(Auth::user()->isAdmin()) {{-- Adapter la logique de rôles si nécessaire --}}
                                Gestion des Demandes de Congé
                            @else
                                Mes Demandes de Congé
                            @endif
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Nouvelle Demande
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if (session('success'))
    <div class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800">
        <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p> </div> </div>
    </div>
    @endif
    @if (session('error'))
    <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800">
         <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p> </div> </div>
    </div>
    @endif
     @if (session('info'))
    <div class="rounded-md bg-blue-50 p-4 shadow-sm border border-blue-200 dark:bg-blue-900/20 dark:border-blue-800">
        <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-blue-800 dark:text-blue-300">{{ session('info') }}</p> </div> </div>
    </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <form method="GET" action="{{ route('leave-requests.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Filtre Employé (Admin) --}}
                    @if(Auth::user()->isAdmin()) {{-- Adapter la logique de rôles si nécessaire --}}
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Employé</label>
                            <select name="employee_id" id="employee_id" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                                <option value="">Tous</option>
                                @foreach($employees as $emp) {{-- Assumes $employees passed --}}
                                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->user->name ?? 'ID: '.$emp->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        {{-- Espace réservé si non admin pour garder l'alignement --}}
                         <div></div>
                    @endif

                     {{-- Filtre Statut --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Statut</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">Tous</option> {{-- Utilise '' au lieu de 'all' --}}
                             {{-- Assumes $statuses is [value => label] --}}
                            @foreach($statuses as $statusValue => $statusLabel)
                                <option value="{{ $statusValue }}" {{ request('status') == $statusValue ? 'selected' : '' }}>
                                    {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtre Date Début --}}
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Début Après Le</label>
                        <input type="date" name="date_from" id="date_from"
                               value="{{ request('date_from') }}"
                               class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    </div>

                    {{-- Filtre Date Fin --}}
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Début Avant Le</label>
                        <input type="date" name="date_to" id="date_to"
                               value="{{ request('date_to') }}"
                               class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                        Filtrer
                    </button>
                    {{-- Bouton Réinitialiser supprimé --}}
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Demandes -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                         <tr>
                            {{-- Helper function for sort icon --}}
                            @php
                            $sortIcon = function($field) { /* ... Same helper as before ... */
                                if (request('sort') == $field) { return request('direction', 'asc') == 'asc' ? '<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>' : '<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>'; } return '<svg class="invisible h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>';
                            };
                            @endphp

                            {{-- Colonne Employé (Admin seulement) --}}
                            @if(Auth::user()->isAdmin())
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <a href="{{ route('leave-requests.index', array_merge(request()->query(), ['sort' => 'employee_name', 'direction' => (request('sort') == 'employee_name' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                        Employé
                                        <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'employee_name' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('employee_name') !!} </span>
                                    </a>
                                </th>
                            @endif
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('leave-requests.index', array_merge(request()->query(), ['sort' => 'leave_type', 'direction' => (request('sort') == 'leave_type' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Type Congé
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'leave_type' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('leave_type') !!} </span>
                                </a>
                            </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('leave-requests.index', array_merge(request()->query(), ['sort' => 'start_date', 'direction' => (request('sort') == 'start_date' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Date Début
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'start_date' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('start_date') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('leave-requests.index', array_merge(request()->query(), ['sort' => 'end_date', 'direction' => (request('sort') == 'end_date' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Date Fin
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'end_date' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('end_date') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('leave-requests.index', array_merge(request()->query(), ['sort' => 'duration_days', 'direction' => (request('sort') == 'duration_days' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Durée (j)
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'duration_days' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('duration_days') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('leave-requests.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => (request('sort') == 'status' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Statut
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'status' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('status') !!} </span>
                                </a>
                            </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('leave-requests.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => (request('sort') == 'created_at' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Soumise le
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'created_at' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('created_at') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse ($leaveRequests as $request)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                 {{-- Colonne Employé --}}
                                @if(Auth::user()->isAdmin())
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $request->employee->user->name ?? 'N/A' }}</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $request->leaveType->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $request->start_date->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $request->end_date->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $request->duration_days ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                     {{-- Badge Statut --}}
                                      @php
                                        // Assuming $statuses contains [value => label]
                                        $statusLabel = $statuses[$request->status] ?? ucfirst($request->status);
                                        $statusClass = '';
                                        switch($request->status) {
                                            case 'approuve': $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'; break;
                                            case 'refuse': $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'; break;
                                            case 'annule': $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'; break;
                                            case 'pending': default: $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; break;
                                        }
                                    @endphp
                                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $request->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('leave-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Voir">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                         {{-- Annuler (action de suppression) --}}
                                         @if($request->status === 'pending' && (Auth::user()->isAdmin() || Auth::id() == $request->employee?->user_id) )
                                             <form action="{{ route('leave-requests.destroy', $request->id) }}" method="POST" class="inline" onsubmit="return confirm('Voulez-vous vraiment annuler cette demande ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Annuler">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                         @endif
                                          {{-- Pas d'icône Edit ici en général --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                             {{-- Calcul du colspan dynamique --}}
                             @php $colspan = Auth::user()->isAdmin() ? 8 : 7; @endphp
                             <tr>
                                <td colspan="{{ $colspan }}" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                    Aucune demande de congé trouvée
                                    {{-- Lien Réinitialiser omis pour coller au modèle --}}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination placée ici comme dans le modèle --}}
            <div class="mt-4">
                {{ $leaveRequests->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection