{{-- resources/views/leave_requests/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mes Demandes de Congé') }} {{-- Adapter titre si Admin/RH --}}
            </h2>
            {{-- Bouton pour faire une nouvelle demande --}}
            <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('+ Nouvelle Demande') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                     {{-- Messages flash --}}
                    @if (session('success')) <div class="mb-4 bg-green-100 ...">{{ session('success') }}</div> @endif
                    @if (session('error')) <div class="mb-4 bg-red-100 ...">{{ session('error') }}</div> @endif
                    @if (session('info')) <div class="mb-4 bg-blue-100 ...">{{ session('info') }}</div> @endif

                    {{-- Tableau des demandes --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left ...">
                            <thead class="text-xs text-gray-700 uppercase ...">
                                <tr>
                                    {{-- Colonne Employé (si admin/RH) --}}
                                    {{-- @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('rh_manager')) --}}
                                    {{-- Remplacer par ta vraie logique de rôle --}}
                                    @if(true) {{-- Afficher pour l'instant --}}
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
                                    <tr class="bg-white border-b dark:bg-gray-800 ...">
                                         {{-- Colonne Employé (si admin/RH) --}}
                                         @if(true) {{-- Afficher pour l'instant --}}
                                            <td class="px-6 py-4">{{ $request->employee->user->name ?? 'N/A' }}</td>
                                         @endif
                                        <td class="px-6 py-4">{{ $request->leaveType->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $request->start_date->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">{{ $request->end_date->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">{{ $request->duration_days ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @switch($request->status)
                                                    @case('approved') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break
                                                    @case('rejected')
                                                    @case('canceled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                                    @default bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 {{-- pending --}}
                                                @endswitch
                                            ">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $request->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             <a href="{{ route('leave-requests.show', $request->id) }}" class="font-medium text-blue-600 ...">Voir</a>
                                             {{-- Modifier/Annuler seulement si 'pending' et si c'est sa demande ou si admin/RH ? --}}
                                             @if($request->status === 'pending' /* && Auth::id() == $request->employee->user_id */ )
                                                {{-- <a href="{{ route('leave-requests.edit', $request->id) }}" class="font-medium text-indigo-600 ...">Modifier</a> --}}
                                                <form action="{{ route('leave-requests.destroy', $request->id) }}" method="POST" onsubmit="return confirm('Annuler cette demande ?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 ...">Annuler</button>
                                                </form>
                                             @endif
                                              {{-- Ajouter boutons Approuver/Rejeter pour Manager/RH --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ true ? 8 : 7 }}" class="px-6 py-4 text-center ...">Aucune demande de congé trouvée.</td></tr>
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