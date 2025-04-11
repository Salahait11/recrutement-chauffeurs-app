{{-- resources/views/candidates/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        {{-- Conteneur Principal pour Titre, Filtres/Recherche, Bouton Ajouter --}}
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Candidats') }}
            </h2>

            {{-- Conteneur pour les Filtres/Recherche --}}
            <div class="flex flex-wrap items-center gap-4">

                {{-- Formulaire de Recherche (Adapté pour inclure statut) --}}
                <div class="w-full sm:w-auto">
                    <form method="GET" action="{{ route('candidates.index') }}" id="searchForm" class="flex items-center gap-2">
                        {{-- Champ caché pour garder le filtre statut lors d'une recherche --}}
                        @if(request('status') && request('status') != 'all')
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif

                        <div class="relative flex-grow">
                            <input type="text" name="search" placeholder="Rechercher nom, email..."
                                   class="block w-full sm:w-56 rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm pe-10"
                                   value="{{ request('search') ?? '' }}">
                            <span class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 pointer-events-none"><svg class="h-5 w-5">...</svg></span> {{-- Loupe --}}
                            @if(request('search'))
                                {{-- Lien pour effacer SEULEMENT la recherche (garde le statut) --}}
                                <a href="{{ route('candidates.index', ['status' => request('status')]) }}" class="absolute inset-y-0 end-10 flex items-center pe-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer" title="Effacer la recherche">
                                     <svg class="h-4 w-4">...</svg> {{-- Croix --}}
                                </a>
                            @endif
                        </div>
                        {{-- Bouton Rechercher --}}
                        <button type="submit" class="px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                            Rechercher
                        </button>
                    </form>
                </div>

                {{-- Filtre Statut (Adapté pour inclure recherche) --}}
                <div class="w-full sm:w-auto">
                    <form method="GET" action="{{ route('candidates.index') }}" id="statusFilterForm">
                        {{-- Champ caché pour garder la recherche lors du filtrage par statut --}}
                        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif

                        <label for="status_filter" class="sr-only">Filtrer par Statut</label>
                        <select name="status" id="status_filter" onchange="this.form.submit();" {{-- Soumet au changement --}}
                                class="block w-full sm:w-48 rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="all" {{ !request('status') || request('status') == 'all' ? 'selected' : '' }}>-- Tous les Statuts --</option>
                            {{-- $statuses doit être passé par le contrôleur --}}
                            @foreach($statuses ?? [] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- Bouton Réinitialiser TOUS les filtres --}}
                @if(request('search') || (request('status') && request('status') != 'all'))
                    <div class="w-full sm:w-auto">
                        <a href="{{ route('candidates.index') }}" class="inline-flex items-center px-4 py-2 text-xs bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Réinitialiser Tout
                        </a>
                    </div>
                @endif

            </div> {{-- Fin Conteneur Filtres/Recherche --}}

            {{-- Bouton Ajouter Candidat --}}
            <a href="{{ route('candidates.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
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
                                    <th scope="col" class="px-6 py-3">Expérience</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidates as $candidate)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">
                                            {{ $candidate->first_name }} {{ $candidate->last_name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $candidate->email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $candidate->phone }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $candidate->years_of_experience }} {{ Str::plural('an', $candidate->years_of_experience) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClass = match ($candidate->status) {
                                                    \App\Models\Candidate::STATUS_EMBAUCHE => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                                    \App\Models\Candidate::STATUS_REFUSE => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                                    \App\Models\Candidate::STATUS_OFFRE => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                                    \App\Models\Candidate::STATUS_TEST => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
                                                    \App\Models\Candidate::STATUS_ENTRETIEN => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                                                    \App\Models\Candidate::STATUS_CONTACTE => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100', // STATUS_NOUVEAU
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ \App\Models\Candidate::$statuses[$candidate->status] ?? 'Inconnu' }}
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
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            @if(request('search'))
                                                Aucun candidat trouvé pour la recherche "{{ request('search') }}".
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
