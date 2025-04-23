{{-- resources/views/driving_tests/index.blade.php --}}
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
                            {{-- Icône exemple (Calendrier) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Liste des Tests de Conduite
                        </div>
            </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('driving-tests.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Planifier un Test
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
    {{-- Note: Le message d'erreur a été omis pour correspondre au modèle --}}

    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <form method="GET" action="{{ route('driving-tests.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Filtre par Candidat (si admin) -->
                @if(Auth::user()->isAdmin())
                    <div>
                        <label for="candidate_id" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Candidat</label>
                        <select name="candidate_id" id="candidate_id" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        <option value="">Tous</option>
                            @foreach($candidates as $candidate)
                                <option value="{{ $candidate->id }}" {{ request('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                    {{ $candidate->first_name }} {{ $candidate->last_name }}
                                </option>
                            @endforeach
                    </select>
                </div>
                @endif

                    <!-- Filtre par statut -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Statut</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">Tous</option>
                            @foreach(App\Models\DrivingTest::getStatusList() as $statusValue => $statusLabel)
                                <option value="{{ $statusValue }}" {{ request('status') == $statusValue ? 'selected' : '' }}>
                                    {{ $statusLabel }}
                                </option>
                            @endforeach
                    </select>
                </div>

                    <!-- Filtre par date début -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Date de début</label>
                        <input type="date" name="date_from" id="date_from"
                            value="{{ request('date_from') }}"
                            class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    </div>

                    <!-- Filtre par date fin -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Date de fin</label>
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
                    {{-- Note: Le bouton Réinitialiser a été omis pour correspondre au modèle --}}
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Tests de Conduite -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            {{-- En-tête Candidat (avec tri) --}}
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('driving-tests.index', array_merge(request()->except('sort', 'direction'), ['sort' => 'candidate', 'direction' => request('sort') == 'candidate' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Candidat
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'candidate' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                        @if(request('sort') == 'candidate')
                                            @if(request('direction', 'asc') == 'asc')
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else {{-- Placeholder Icon --}}
                                            <svg class="invisible h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                             {{-- En-tête Date & Heure (avec tri) --}}
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('driving-tests.index', array_merge(request()->except('sort', 'direction'), ['sort' => 'test_date', 'direction' => request('sort') == 'test_date' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Date & Heure
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'test_date' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                        @if(request('sort') == 'test_date')
                                            @if(request('direction', 'asc') == 'asc')
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else {{-- Placeholder Icon --}}
                                            <svg class="invisible h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                             {{-- En-tête Véhicule (avec tri - optionnel, dépend si pertinent) --}}
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('driving-tests.index', array_merge(request()->except('sort', 'direction'), ['sort' => 'vehicle', 'direction' => request('sort') == 'vehicle' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Véhicule
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'vehicle' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                         @if(request('sort') == 'vehicle')
                                            @if(request('direction', 'asc') == 'asc')
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else {{-- Placeholder Icon --}}
                                            <svg class="invisible h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                             {{-- En-tête Évaluateur (avec tri) --}}
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('driving-tests.index', array_merge(request()->except('sort', 'direction'), ['sort' => 'evaluator', 'direction' => request('sort') == 'evaluator' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Évaluateur
                                     <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'evaluator' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                        @if(request('sort') == 'evaluator')
                                            @if(request('direction', 'asc') == 'asc')
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else {{-- Placeholder Icon --}}
                                            <svg class="invisible h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            {{-- En-tête Statut (avec tri) --}}
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('driving-tests.index', array_merge(request()->except('sort', 'direction'), ['sort' => 'status', 'direction' => request('sort') == 'status' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Statut
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'status' ? '' : 'invisible' }} group-hover:visible group-focus:visible">
                                        @if(request('sort') == 'status')
                                            @if(request('direction', 'asc') == 'asc')
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>
                                            @else
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                            @endif
                                        @else
                                            <svg class="invisible h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Résultats
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                                </tr>
                            </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($drivingTests as $test)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                {{-- Cellule Candidat (avec avatar/initiales) --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($test->candidate && $test->candidate->photo)
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $test->candidate->photo) }}" alt="{{ $test->candidate->first_name }} {{ $test->candidate->last_name }}">
                                            @elseif($test->candidate)
                                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                    <span class="text-blue-600 dark:text-blue-300 font-medium">{{ substr($test->candidate->first_name, 0, 1) }}{{ substr($test->candidate->last_name, 0, 1) }}</span>
                                                </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                    <span class="text-gray-500 dark:text-gray-400">?</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                @if($test->candidate)
                                                    <a href="{{ route('candidates.show', $test->candidate->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $test->candidate->first_name }} {{ $test->candidate->last_name }}
                                                    </a>
                                                 @else
                                                    <span class="italic text-gray-400 dark:text-gray-500">N/A</span>
                                                 @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $test->test_date->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        @if($test->vehicle)
                                            {{ $test->vehicle->brand }} {{ $test->vehicle->model }}
                                            <span class="text-gray-500 dark:text-gray-400">({{ $test->vehicle->plate_number }})</span>
                                             @else
                                                 -
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $test->evaluator->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ App\Models\DrivingTest::getStatusBadgeClass($test->status) }}">
                                        {{ App\Models\DrivingTest::getStatusList()[$test->status] ?? $test->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    @if($test->status === 'reussi' || $test->status === 'echoue')
                                        <div class="space-y-1">
                                            <div class="flex items-center">
                                                <span class="font-medium">Score:</span>
                                                <span class="ml-2">{{ $test->score ?? 'N/A' }}/100</span>
                                            </div>
                                            @if($test->results_summary)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ Str::limit($test->results_summary, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">-</span>
                                             @endif
                                         </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        {{-- Actions avec style du modèle --}}
                                        <a href="{{ route('driving-tests.show', $test->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="{{ __('Voir') }}">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('driving-tests.edit', $test->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="{{ __('Modifier') }}">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('driving-tests.destroy', $test->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="{{ __('Supprimer') }}" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer ce test ?') }}')">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                         </td>
                                    </tr>
                                @empty
                            <tr>
                                {{-- Message vide stylisé comme le modèle --}}
                                <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                    Aucun test de conduite trouvé
                                     {{-- Note: Le lien de réinitialisation des filtres a été omis pour correspondre au modèle --}}
                                </td>
                            </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

            {{-- Pagination placée ici comme dans le modèle --}}
            <div class="mt-4">
                {{ $drivingTests->appends(request()->query())->links() }} {{-- Ajout de appends pour conserver les filtres --}}
            </div>
        </div>
    </div>
</div>
@endsection