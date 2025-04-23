{{-- resources/views/leave_types/index.blade.php --}}
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
                            {{-- Icône Calendar Days (Exemple) --}}
                             <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0h18M-4.5 12h22.5" />
                             </svg>
                            Gestion des Types de Congé
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('admin.leave-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Nouveau Type
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if (session('success'))
    <div class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p> </div> </div> </div>
    @endif
    @if (session('error'))
    <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p> </div> </div> </div>
    @endif

    <!-- Filtres (Optionnel - Ajouté pour la cohérence structurelle) -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.leave-types.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search_lt" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Recherche</label>
                        <input type="text" name="search" id="search_lt" value="{{ request('search') }}" placeholder="Nom, description..."
                               class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    </div>
                    <div>
                        <label for="active_filter_lt" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Statut</label>
                        <select name="is_active" id="active_filter_lt" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">Tous</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                            Filtrer
                        </button>
                        <a href="{{ route('admin.leave-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                            Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Liste des Types de Congé -->
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.leave-types.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => (request('sort') == 'name' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Nom
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'name' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('name') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.leave-types.index', array_merge(request()->query(), ['sort' => 'requires_approval', 'direction' => (request('sort') == 'requires_approval' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Approbation Requise
                                     <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'requires_approval' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('requires_approval') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.leave-types.index', array_merge(request()->query(), ['sort' => 'affects_balance', 'direction' => (request('sort') == 'affects_balance' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Affecte Solde
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'affects_balance' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('affects_balance') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.leave-types.index', array_merge(request()->query(), ['sort' => 'is_active', 'direction' => (request('sort') == 'is_active' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Actif
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'is_active' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('is_active') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Couleur</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                         @forelse ($leaveTypes as $leaveType)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $leaveType->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $leaveType->requires_approval ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $leaveType->requires_approval ? 'Oui' : 'Non' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $leaveType->affects_balance ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $leaveType->affects_balance ? 'Oui' : 'Non' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $leaveType->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ $leaveType->is_active ? 'Oui' : 'Non' }}
                                    </span>
                                </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     @if($leaveType->color_code)
                                        <span class="inline-block w-4 h-4 rounded-full border border-gray-300 dark:border-gray-600 shadow-sm" style="background-color: {{ $leaveType->color_code }}" title="{{ $leaveType->color_code }}"></span>
                                        {{-- <span class="ml-1 text-xs font-mono">{{ $leaveType->color_code }}</span> --}} {{-- Optionnel: afficher code couleur --}}
                                     @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                     @endif
                                 </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                     <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.leave-types.edit', $leaveType->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="Modifier">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                         <form action="{{ route('admin.leave-types.destroy', $leaveType->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type ? (Possible seulement s\'il n\'est pas utilisé)');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Supprimer">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                             <tr><td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                Aucun type de congé défini.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="mt-4">
                {{ $leaveTypes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection