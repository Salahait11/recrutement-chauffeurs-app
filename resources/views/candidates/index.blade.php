{{-- resources/views/candidates/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Liste des Candidats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Afficher le message de succès --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                    {{-- Bouton pour ajouter un candidat (lien vers la future page de création) --}}
                    <a href="{{ route('candidates.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mb-4">
                        {{ __('Ajouter un Candidat') }}
                    </a>

                    {{-- Tableau pour afficher les candidats --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nom</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Téléphone</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- On va boucler sur les candidats ici --}}
                                @forelse ($candidates as $candidate)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $candidate->first_name }} {{ $candidate->last_name }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $candidate->email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $candidate->phone }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{-- Utilisation d'une méthode pour afficher le statut de manière lisible (on la créera plus tard si besoin) --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $candidate->status === 'hired' ? 'bg-green-100 text-green-800' : ($candidate->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                                {{ ucfirst($candidate->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            {{-- Liens pour Voir, Modifier, Supprimer (on les implémentera plus tard) --}}
                                            <a href="{{ route('candidates.show', $candidate->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">Voir</a>
                                            <a href="{{ route('candidates.edit', $candidate->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline mr-3">Modifier</a>
                                            {{-- Le bouton supprimer nécessite un formulaire --}}
                                            {{-- <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</a> --}}
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Message si aucun candidat n'est trouvé --}}
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Aucun candidat trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Ajouter la pagination ici si nécessaire plus tard --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>