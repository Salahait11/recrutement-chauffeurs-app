{{-- resources/views/driving_tests/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Tests de Conduite') }}
            </h2>

            {{-- Formulaire Filtres --}}
            <form method="GET" action="{{ route('driving-tests.index') }}" class="flex flex-wrap items-end gap-3 text-sm flex-grow md:flex-grow-0">

                {{-- Filtre Candidat (si admin) --}}
                @if(Auth::user()->isAdmin())
                <div class="flex-grow sm:flex-grow-0">
                    <label for="candidate_filter_dt" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Candidat</label>
                    <select name="candidate_id" id="candidate_filter_dt" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                        <option value="">Tous</option>
                        @foreach($candidates as $candidate) <option value="{{ $candidate->id }}" {{ ($candidateFilter ?? null) == $candidate->id ? 'selected' : '' }}>{{ $candidate->first_name }} {{ $candidate->last_name }}</option> @endforeach
                    </select>
                </div>
                @endif

                {{-- Filtre Statut --}}
                <div class="flex-grow sm:flex-grow-0">
                    <label for="status_filter_dt" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Statut</label>
                    <select name="status" id="status_filter_dt" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                        <option value="all" {{ !$statusFilter ? 'selected' : '' }}>Tous</option>
                        @foreach($statuses as $status) <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option> @endforeach
                    </select>
                </div>

                {{-- Filtre Date Début --}}
                <div class="flex-grow sm:flex-grow-0">
                    <label for="date_from" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Test Du</label>
                    <input type="date" name="date_from" id="date_from" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs" value="{{ $dateFromFilter ?? '' }}">
                </div>
                {{-- Filtre Date Fin --}}
                <div class="flex-grow sm:flex-grow-0">
                    <label for="date_to" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Au</label>
                    <input type="date" name="date_to" id="date_to" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs" value="{{ $dateToFilter ?? '' }}">
                </div>

                {{-- Boutons --}}
                <div class="flex items-center gap-2 pt-5">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Filtrer</button>
                    @if($statusFilter || $candidateFilter || $dateFromFilter || $dateToFilter)
                        <a href="{{ route('driving-tests.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" title="Réinitialiser les filtres">↻</a>
                    @endif
                </div>
            </form> {{-- Fin Formulaire Filtres --}}

            {{-- Bouton Planifier Test --}}
            <div class="w-full sm:w-auto mt-4 sm:mt-0">
               <a href="{{ route('driving-tests.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                   {{ __('Planifier un Test') }}
               </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                     @if (session('success')) <div class="mb-4 bg-green-100 ...">{{ session('success') }}</div> @endif
                     @if (session('error')) <div class="mb-4 bg-red-100 ...">{{ session('error') }}</div> @endif
                     @if (session('info')) <div class="mb-4 bg-blue-100 ...">{{ session('info') }}</div> @endif

                    {{-- Tableau des tests --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Candidat</th>
                                    <th scope="col" class="px-6 py-3">Date & Heure</th>
                                    <th scope="col" class="px-6 py-3">Véhicule</th>
                                    <th scope="col" class="px-6 py-3">Évaluateur</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">Résultat</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($drivingTests as $test)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                         <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">@if($test->candidate)<a href="{{ route('candidates.show', $test->candidate->id) }}" class="hover:underline">{{ $test->candidate->first_name }} {{ $test->candidate->last_name }}</a>@else<span class="italic text-gray-400">N/A</span>@endif</td>
                                         <td class="px-6 py-4">{{ $test->test_date->format('d/m/Y H:i') }}</td>
                                         <td class="px-6 py-4 text-xs">{{ $test->vehicle ? ($test->vehicle->brand.' '.$test->vehicle->model.' ('.$test->vehicle->plate_number.')') : '-' }}</td>
                                         <td class="px-6 py-4">{{ $test->evaluator->name ?? '-' }}</td>
                                         <td class="px-6 py-4"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @switch($test->status) @case('completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break @case('canceled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break @default bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @endswitch">{{ ucfirst($test->status) }}</span></td>
                                         <td class="px-6 py-4"> @if($test->status === 'completed') @if($test->passed === true)<span class="text-green-600 font-semibold">Réussi</span>@elseif($test->passed === false)<span class="text-red-600 font-semibold">Échoué</span>@else<span class="text-gray-500 italic">N/D</span>@endif @else - @endif </td>
                                         <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                              <a href="{{ route('driving-tests.show', $test->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir</a>
                                              <a href="{{ route('driving-tests.edit', $test->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                              <form action="{{ route('driving-tests.destroy', $test->id) }}" method="POST" onsubmit="return confirm('Supprimer ce test ?');" class="inline">@csrf @method('DELETE')<button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button></form>
                                         </td>
                                    </tr>
                                @empty
                                     @php $colspan = Auth::user()->isAdmin() ? 8 : 7; @endphp {{-- Ajuster colspan --}}
                                     <tr><td colspan="{{ $colspan }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                          Aucun test trouvé pour les critères sélectionnés.
                                           @if($statusFilter || $candidateFilter || $dateFromFilter || $dateToFilter)<a href="{{ route('driving-tests.index') }}" class="ml-2 text-sm text-blue-500 hover:underline">(Réinitialiser les filtres)</a>@endif
                                     </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="mt-4"> {{ $drivingTests->links() }} </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>