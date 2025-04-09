{{-- resources/views/leave_requests/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        {{-- En-tête avec titre, formulaire de filtres/recherche et bouton Ajouter --}}
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{-- Adapter le titre si ce n'est pas l'admin --}}
                @if(Auth::user()->isAdmin())
                    {{ __('Gestion des Demandes de Congé') }}
                @else
                    {{ __('Mes Demandes de Congé') }}
                @endif
            </h2>

            {{-- Formulaire unique pour tous les filtres --}}
            <form method="GET" action="{{ route('leave-requests.index') }}" class="flex flex-wrap items-end gap-3 text-sm flex-grow md:flex-grow-0">

                {{-- Filtre Employé (Seulement pour Admin/Manager) --}}
                {{-- !! Remplacer par votre logique de rôles/permissions !! --}}
                @if(Auth::user()->isAdmin())
                    <div class="flex-grow sm:flex-grow-0">
                        <label for="employee_filter_lr" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Employé</label>
                        <select name="employee_id" id="employee_filter_lr" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                            <option value="">Tous</option>
                            {{-- $employees est passé par le contrôleur --}}
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ ($employeeFilter ?? null) == $emp->id ? 'selected' : '' }}> {{-- Utilise $employeeFilter --}}
                                    {{ $emp->user->name ?? 'ID: '.$emp->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Filtre Statut --}}
                 <div class="flex-grow sm:flex-grow-0">
                    <label for="status_filter_lr" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Statut</label>
                    <select name="status" id="status_filter_lr" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                        <option value="all" {{ !$statusFilter ? 'selected' : '' }}>Tous</option>
                         {{-- $statuses est passé par le contrôleur --}}
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                 </div>

                 {{-- Filtre Date Début Période --}}
                 <div class="flex-grow sm:flex-grow-0">
                     <label for="date_from" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Début Après Le</label>
                     <input type="date" name="date_from" id="date_from"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                            value="{{ $dateFromFilter ?? '' }}"> {{-- Utilise la variable passée --}}
                 </div>

                  {{-- Filtre Date Fin Période --}}
                 <div class="flex-grow sm:flex-grow-0">
                     <label for="date_to" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Début Avant Le</label>
                     <input type="date" name="date_to" id="date_to"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                            value="{{ $dateToFilter ?? '' }}"> {{-- Utilise la variable passée --}}
                 </div>

                 {{-- Boutons --}}
                 <div class="flex items-center gap-2 pt-5"> {{-- pt-5 pour aligner approx. --}}
                     <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                         Filtrer
                     </button>
                     {{-- Bouton Réinitialiser --}}
                     @if(request('employee_id') || $statusFilter || $dateFromFilter || $dateToFilter)
                         <a href="{{ route('leave-requests.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" title="Réinitialiser les filtres">
                             ↻ {{-- Symbole Recharger/Reset --}}
                         </a>
                     @endif
                 </div>
            </form> {{-- Fin Formulaire Filtres --}}

             {{-- Bouton Nouvelle Demande (à droite ou en dessous sur mobile) --}}
             <div class="w-full sm:w-auto sm:ml-auto mt-4 sm:mt-0"> {{-- sm:ml-auto pour pousser à droite --}}
                <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('+ Nouvelle Demande') }}
                </a>
             </div>

         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                     {{-- Messages Flash --}}
                    @if (session('success')) <div class="mb-4 bg-green-100 ...">{{ session('success') }}</div> @endif
                    @if (session('error')) <div class="mb-4 bg-red-100 ...">{{ session('error') }}</div> @endif
                    @if (session('info')) <div class="mb-4 bg-blue-100 ...">{{ session('info') }}</div> @endif


                    {{-- Tableau des demandes --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    {{-- !! Adapter condition avec rôles !! --}}
                                    @if(Auth::user()->isAdmin())
                                        <th scope="col" class="px-6 py-3">Employé</th>
                                    @endif
                                    <th scope="col" class="px-6 py-3">Type Congé</th>
                                    <th scope="col" class="px-6 py-3">Date Début</th>
                                    <th scope="col" class="px-6 py-3">Date Fin</th>
                                    <th scope="col" class="px-6 py-3">Durée (j)</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">Soumise le</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaveRequests as $request)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                         {{-- Colonne Employé --}}
                                         @if(Auth::user()->isAdmin())
                                            <td class="px-6 py-4">{{ $request->employee->user->name ?? 'N/A' }}</td>
                                         @endif
                                        <td class="px-6 py-4">{{ $request->leaveType->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $request->start_date->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">{{ $request->end_date->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">{{ $request->duration_days ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                             {{-- Badge Statut --}}
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @switch($request->status)
                                                    @case('approved') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break
                                                    @case('rejected') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                                    @case('canceled') bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200 @break
                                                    @default bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 {{-- pending --}}
                                                @endswitch
                                            ">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $request->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             <a href="{{ route('leave-requests.show', $request->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir</a>
                                             {{-- Annuler seulement si 'pending' et si admin OU propriétaire --}}
                                             {{-- !! Adapter condition avec rôles !! --}}
                                             @if($request->status === 'pending' && (Auth::user()->isAdmin() || Auth::id() == $request->employee?->user_id) )
                                                <form action="{{ route('leave-requests.destroy', $request->id) }}" method="POST" onsubmit="return confirm('Annuler cette demande ?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Annuler</button>
                                                </form>
                                             @endif
                                              {{-- Bouton Approuver/Rejeter pour Admin/Manager ? (Géré sur la page show) --}}
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Calcul du colspan dynamique --}}
                                    @php $colspan = Auth::user()->isAdmin() ? 8 : 7; @endphp
                                    <tr><td colspan="{{ $colspan }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                         {{-- Message adapté aux filtres actifs --}}
                                         @if(request('employee_id') || $statusFilter || $dateFromFilter || $dateToFilter)
                                             Aucune demande trouvée pour les critères sélectionnés.
                                         @else
                                             Aucune demande de congé trouvée.
                                         @endif
                                         {{-- Lien Réinitialiser --}}
                                         @if(request('employee_id') || $statusFilter || $dateFromFilter || $dateToFilter)
                                             <a href="{{ route('leave-requests.index') }}" class="ml-2 text-sm text-blue-500 hover:underline">(Voir tout)</a>
                                         @endif
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $leaveRequests->links() }}
                    </div>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>