{{-- resources/views/candidates/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Candidats') }}
            </h2>

            {{-- Formulaire de Recherche --}}
            <div class="w-full sm:w-auto">
                <form method="GET" action="{{ route('candidates.index') }}" class="flex flex-wrap items-center gap-2">
                    <div class="relative flex-grow">
                        <input type="text" name="search" placeholder="Rechercher nom, email..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 text-sm pe-10"
                            value="{{ $search ?? '' }}">

                        <span class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 pointer-events-none">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </span>
                    </div>

                    <button type="submit"
                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-500 transition">
                        Rechercher
                    </button>

                    @if($search)
                        <a href="{{ route('candidates.index') }}"
                           class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            <a href="{{ route('candidates.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('+ Ajouter Candidat') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 dark:text-green-200 dark:border-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Tableau des candidats --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nom</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Téléphone</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidates as $candidate)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $candidate->first_name }} {{ $candidate->last_name }}
                                        </th>
                                        <td class="px-6 py-4">{{ $candidate->email }}</td>
                                        <td class="px-6 py-4">{{ $candidate->phone }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $candidate->status === 'hired' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : ($candidate->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100') }}">
                                                {{ ucfirst($candidate->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ route('candidates.show', $candidate->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir</a>
                                            <a href="{{ route('candidates.edit', $candidate->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                            <form method="POST" action="{{ route('candidates.destroy', $candidate->id) }}" onsubmit="return confirm('Supprimer ce candidat ?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            @if($search)
                                                Aucun candidat trouvé pour la recherche "{{ $search }}".
                                            @else
                                                Aucun candidat trouvé.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $candidates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
