{{-- resources/views/admin/absences/index.blade.php --}}
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
                            {{-- Icône User Clock (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Gestion des Absences
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('admin.absences.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Nouvelle Absence
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

    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.absences.index') }}" class="space-y-4">
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="employee_id_filter" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Filtrer par Employé</label>
                        <select name="employee_id" id="employee_id_filter" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">-- Tous --</option>
                            @foreach($employees as $emp) {{-- Assumes $employees passed --}}
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->user->name ?? 'ID: '.$emp->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Placeholder pour d'autres filtres si besoin --}}
                    <div></div>
                    <div></div>
                    <div></div>
                 </div>
                  <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /> </svg>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Absences -->
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
                                <a href="{{ route('admin.absences.index', array_merge(request()->query(), ['sort' => 'employee_name', 'direction' => (request('sort') == 'employee_name' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Employé
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'employee_name' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('employee_name') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.absences.index', array_merge(request()->query(), ['sort' => 'absence_date', 'direction' => (request('sort') == 'absence_date' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Date
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'absence_date' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('absence_date') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{-- Sorting by time might be complex, usually sort by date --}}
                                Heures (Début/Fin)
                            </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.absences.index', array_merge(request()->query(), ['sort' => 'reason_type', 'direction' => (request('sort') == 'reason_type' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Motif
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'reason_type' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('reason_type') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.absences.index', array_merge(request()->query(), ['sort' => 'is_justified', 'direction' => (request('sort') == 'is_justified' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Justifiée
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'is_justified' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('is_justified') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.absences.index', array_merge(request()->query(), ['sort' => 'recorder_name', 'direction' => (request('sort') == 'recorder_name' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Enregistrée par
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'recorder_name' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('recorder_name') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                         @forelse ($absences as $absence)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                     {{-- Colonne Employé avec Avatar/Initiales --}}
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($absence->employee->user && $absence->employee->user->profile_photo_path) {{-- Adapt field names --}}
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $absence->employee->user->profile_photo_path) }}" alt="{{ $absence->employee->user->name ?? '' }}">
                                            @elseif($absence->employee->user)
                                                @php /* Simple initials logic */ $nameParts = explode(' ', $absence->employee->user->name ?? 'N A'); $initials = count($nameParts) >= 2 ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1)) : strtoupper(substr($absence->employee->user->name ?? 'NA', 0, 1)); @endphp
                                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center"> <span class="text-blue-600 dark:text-blue-300 font-medium">{{ $initials }}</span> </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"> <span class="text-gray-500 dark:text-gray-400">?</span> </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $absence->employee->user->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $absence->absence_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $absence->start_time ? \Carbon\Carbon::parse($absence->start_time)->format('H:i') : '-' }}
                                     -
                                    {{ $absence->end_time ? \Carbon\Carbon::parse($absence->end_time)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $absence->reason_type ?? '-' }} {{-- Consider adding reason details in tooltip or modal --}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $absence->is_justified ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ $absence->is_justified ? 'Oui' : 'Non' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    {{ $absence->recorder->name ?? 'Système' }} <br>
                                    {{ $absence->created_at->format('d/m/y H:i')}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        {{-- Lien Edit --}}
                                        <a href="{{ route('admin.absences.edit', $absence->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="Modifier">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        {{-- Formulaire Supprimer --}}
                                         <form action="{{ route('admin.absences.destroy', $absence->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement d\'absence ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Supprimer">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                Aucune absence enregistrée trouvée.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="mt-4">
                {{ $absences->appends(request()->query())->links() }}
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('admin.absences.pdf', request()->query()) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Télécharger PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection