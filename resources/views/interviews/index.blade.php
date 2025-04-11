{{-- resources/views/interviews/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Entretiens') }}
            </h2>

            <div class="flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('interviews.index') }}" class="flex flex-wrap items-end gap-3 text-sm">
                    @if(Auth::user()->isAdmin())
                        <div>
                            <label for="candidate_filter_int" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Candidat</label>
                            <select name="candidate_id" id="candidate_filter_int" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                                <option value="">Tous les candidats</option>
                                @foreach($candidates as $candidate)
                                    <option value="{{ $candidate->id }}" {{ $candidateFilter == $candidate->id ? 'selected' : '' }}>
                                        {{ $candidate->first_name }} {{ $candidate->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label for="status_filter_int" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Statut</label>
                        <select name="status" id="status_filter_int" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                            <option value="">Tous les statuts</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $statusFilter === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_from" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Du</label>
                        <input type="date" name="date_from" id="date_from"
                               class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                               value="{{ $dateFromFilter }}">
                    </div>

                    <div>
                        <label for="date_to" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Au</label>
                        <input type="date" name="date_to" id="date_to"
                               class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                               value="{{ $dateToFilter }}">
                    </div>

                    <div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('Filtrer') }}
                        </button>
                    </div>
                </form>

                <a href="{{ route('interviews.create') }}" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    {{ __('Nouvel Entretien') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left">Candidat</th>
                                    <th scope="col" class="px-6 py-3 text-left">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($interviews as $interview)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('candidates.show', $interview->candidate) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $interview->interview_date->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ ucfirst($interview->type) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $interview->status === 'planifié' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 
                                                   ($interview->status === 'terminé' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 
                                                   'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100') }}">
                                                {{ ucfirst($interview->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <a href="{{ route('interviews.edit', $interview) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ __('Modifier') }}
                                            </a>
                                            <form action="{{ route('interviews.destroy', $interview) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet entretien ?');">
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
                                            {{ __('Aucun entretien trouvé.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $interviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>