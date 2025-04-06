{{-- resources/views/offers/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Offres d\'Emploi') }}
            </h2>
             {{-- Bouton pour créer une offre générique (nécessite de choisir le candidat dans le formulaire) --}}
             {{-- Si la méthode OfferController@create est activée et passe $candidates --}}
             {{--
             <a href="{{ route('offers.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                 {{ __('+ Nouvelle Offre') }}
             </a>
              --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Afficher les messages flash --}}
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
                     @if (session('info'))
                        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif

                    {{-- Tableau pour afficher les offres --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Candidat</th>
                                    <th scope="col" class="px-6 py-3">Poste Proposé</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">Créée le</th>
                                    <th scope="col" class="px-6 py-3">Envoyée le</th>
                                    <th scope="col" class="px-6 py-3">Expire le</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($offers as $offer)
                                     <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">{{ $offer->id }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                             @if($offer->candidate)
                                                <a href="{{ route('candidates.show', $offer->candidate->id) }}" class="hover:underline">
                                                    {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $offer->position_offered }}</td>
                                        <td class="px-6 py-4">
                                            {{-- Badge de statut coloré --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @switch($offer->status)
                                                    @case('accepted') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break
                                                    @case('rejected')
                                                    @case('expired')
                                                    @case('withdrawn') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                                    @case('sent') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @break
                                                    @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 {{-- draft --}}
                                                @endswitch
                                            ">
                                                {{ ucfirst($offer->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $offer->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $offer->sent_at ? $offer->sent_at->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4">{{ $offer->expires_at ? $offer->expires_at->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             <a href="{{ route('offers.show', $offer->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir</a>
                                             <a href="{{ route('offers.edit', $offer->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                             <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Aucune offre d'emploi trouvée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $offers->links() }}
                    </div>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w-7xl --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>