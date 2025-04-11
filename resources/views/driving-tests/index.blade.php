{{-- resources/views/driving-tests/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tests de Conduite') }}
            </h2>

            {{-- Filtres --}}
            <div class="flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('driving-tests.index') }}" class="flex flex-wrap items-end gap-3 text-sm">
                    {{-- Filtre Date --}}
                    <div>
                        <label for="date_from" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Du</label>
                        <input type="date" name="date_from" id="date_from"
                               class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                               value="{{ request('date_from') }}">
                    </div>
                    <div>
                        <label for="date_to" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Au</label>
                        <input type="date" name="date_to" id="date_to"
                               class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                               value="{{ request('date_to') }}">
                    </div>

                    {{-- Filtre Statut --}}
                    <div>
                        <label for="status" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Statut</label>
                        <select name="status" id="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                            <option value="">Tous les statuts</option>
                            <option value="{{ App\Models\DrivingTest::STATUS_SCHEDULED }}" {{ request('status') === App\Models\DrivingTest::STATUS_SCHEDULED ? 'selected' : '' }}>Planifié</option>
                            <option value="{{ App\Models\DrivingTest::STATUS_PASSED }}" {{ request('status') === App\Models\DrivingTest::STATUS_PASSED ? 'selected' : '' }}>Réussi</option>
                            <option value="{{ App\Models\DrivingTest::STATUS_FAILED }}" {{ request('status') === App\Models\DrivingTest::STATUS_FAILED ? 'selected' : '' }}>Échoué</option>
                            <option value="{{ App\Models\DrivingTest::STATUS_CANCELED }}" {{ request('status') === App\Models\DrivingTest::STATUS_CANCELED ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('Filtrer') }}
                        </button>
                    </div>
                </form>

                <a href="{{ route('driving-tests.create') }}" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    {{ __('Nouveau Test') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left">Candidat</th>
                                    <th scope="col" class="px-6 py-3 text-left">Date du Test</th>
                                    <th scope="col" class="px-6 py-3 text-left">Véhicule</th>
                                    <th scope="col" class="px-6 py-3 text-left">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($drivingTests as $test)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('candidates.show', $test->candidate) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $test->candidate->first_name }} {{ $test->candidate->last_name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $test->test_date->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $test->vehicle_type ?? 'Non spécifié' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $test->status === App\Models\DrivingTest::STATUS_SCHEDULED ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 
                                                   ($test->status === App\Models\DrivingTest::STATUS_PASSED ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 
                                                   ($test->status === App\Models\DrivingTest::STATUS_FAILED ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : 
                                                   'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100')) }}">
                                                {{ ucfirst($test->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <a href="{{ route('driving-tests.edit', $test) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ __('Modifier') }}
                                            </a>
                                            <form action="{{ route('driving-tests.destroy', $test) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce test ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    {{ __('Supprimer') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            {{ __('Aucun test de conduite trouvé.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $drivingTests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>