{{-- resources/views/offers/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
         {{-- En-tête avec titre et formulaire de filtres --}}
         <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Offres d\'Emploi') }}
            </h2>

            {{-- Formulaire unique pour tous les filtres --}}
            <form method="GET" action="{{ route('offers.index') }}" class="flex flex-wrap items-end gap-3 text-sm flex-grow md:flex-grow-0">

                 {{-- Filtre Candidat --}}
                 <div class="flex-grow sm:flex-grow-0">
                     <label for="candidate_filter_offer" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Candidat</label>
                     <select name="candidate_id" id="candidate_filter_offer" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                         <option value="">-- Tous Candidats --</option>
                         @foreach($candidatesWithOffers as $candidate)
                             <option value="{{ $candidate->id }}" {{ ($candidateFilter ?? null) == $candidate->id ? 'selected' : '' }}>
                                 {{ $candidate->first_name }} {{ $candidate->last_name }}
                             </option>
                         @endforeach
                     </select>
                 </div>

                 {{-- Filtre Statut --}}
                  <div class="flex-grow sm:flex-grow-0">
                     <label for="status_filter_offer" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Statut</label>
                     <select name="status" id="status_filter_offer" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                         <option value="all" {{ !$statusFilter || $statusFilter == 'all' ? 'selected' : '' }}>-- Tous Statuts --</option>
                         @foreach($statuses as $status)
                             <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>
                                 {{ ucfirst($status) }}
                             </option>
                         @endforeach
                     </select>
                  </div>
                  
                  {{-- Filtre Date Début --}}
                  <div class="flex-grow sm:flex-grow-0">
                      <label for="date_from" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Date début</label>
                      <input type="date" name="date_from" id="date_from" value="{{ $dateFrom ?? '' }}" 
                          class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                  </div>
                  
                  {{-- Filtre Date Fin --}}
                  <div class="flex-grow sm:flex-grow-0">
                      <label for="date_to" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Date fin</label>
                      <input type="date" name="date_to" id="date_to" value="{{ $dateTo ?? '' }}" 
                          class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                  </div>

                   {{-- Boutons --}}
                  <div class="flex items-center gap-2 pt-5"> {{-- pt-5 pour aligner approx. --}}
                      <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                          Filtrer
                      </button>
                      {{-- Bouton Réinitialiser --}}
                      @if($statusFilter || $candidateFilter || isset($dateFrom) || isset($dateTo))
                          <a href="{{ route('offers.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" title="Réinitialiser les filtres">
                              ↻ {{-- Symbole Recharger/Reset --}}
                          </a>
                      @endif
                  </div>
             </form> {{-- Fin Formulaire Filtres --}}

              {{-- Le bouton "+ Nouvelle Offre" n'est pas ajouté ici car la création se fait depuis la fiche candidat --}}

         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                     {{-- Messages Flash --}}
                    @if (session('success')) <div class="mb-4 bg-green-100 border border-green-400 text-green-700 dark:text-green-200 dark:border-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div> @endif
                    @if (session('error')) <div class="mb-4 bg-red-100 border border-red-400 text-red-700 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div> @endif
                    @if (session('info')) <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 dark:text-blue-200 dark:border-blue-700 px-4 py-3 rounded relative">{{ session('info') }}</div> @endif


                    {{-- Tableau des offres --}}
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
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
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
                                    <tr><td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Aucune offre trouvée pour les critères sélectionnés.
                                         @if($statusFilter || $candidateFilter || isset($dateFrom) || isset($dateTo))<a href="{{ route('offers.index') }}" class="ml-2 text-sm text-blue-500 hover:underline">(Réinitialiser les filtres)</a>@endif
                                    </td></tr>
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
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>