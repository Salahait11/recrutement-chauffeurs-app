{{-- resources/views/admin/absences/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestion des Absences') }}
            </h2>
            <div class="flex gap-4 items-center">
                {{-- Formulaire de filtre par employé (simple) --}}
                <form method="GET" action="{{ route('admin.absences.index') }}" class="flex items-center gap-2">
                    <label for="employee_filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtrer par Employé:</label>
                    <select name="employee_id" id="employee_filter" onchange="this.form.submit()" class="block w-48 rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Tous --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->user->name ?? 'ID: '.$emp->id }}
                            </option>
                        @endforeach
                    </select>
                     @if(request('employee_id'))
                        <a href="{{ route('admin.absences.index') }}" class="text-sm text-blue-500 hover:underline" title="Effacer le filtre">×</a>
                     @endif
                </form>
                {{-- Bouton Ajouter --}}
                <a href="{{ route('admin.absences.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('+ Nouvelle Absence') }}
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

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Employé</th>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Heures (Début/Fin)</th>
                                    <th scope="col" class="px-6 py-3">Motif</th>
                                    <th scope="col" class="px-6 py-3">Justifiée</th>
                                    <th scope="col" class="px-6 py-3">Enregistrée par</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absences as $absence)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $absence->employee->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">{{ $absence->absence_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            {{ $absence->start_time ? \Carbon\Carbon::parse($absence->start_time)->format('H:i') : '-' }}
                                             /
                                            {{ $absence->end_time ? \Carbon\Carbon::parse($absence->end_time)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4">{{ $absence->reason_type ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $absence->is_justified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $absence->is_justified ? 'Oui' : 'Non' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $absence->recorder->name ?? 'Système' }}<br>
                                            {{ $absence->created_at->format('d/m/y H:i')}}
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             <a href="{{ route('admin.absences.edit', $absence->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                             <form action="{{ route('admin.absences.destroy', $absence->id) }}" method="POST" onsubmit="return confirm('Supprimer cet enregistrement d\'absence ?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Aucune absence enregistrée.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $absences->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>