{{-- resources/views/employees/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
         {{-- En-tête avec titre, formulaire de filtres/recherche et bouton Ajouter --}}
         <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Employés') }}
            </h2>

            {{-- Formulaire unique pour tous les filtres et la recherche --}}
            <form method="GET" action="{{ route('employees.index') }}" class="flex flex-wrap items-end gap-3 text-sm flex-grow md:flex-grow-0">

                {{-- Champ Recherche --}}
                <div class="flex-grow sm:flex-grow-0">
                    <label for="search" class="sr-only">Rechercher</label>
                    <input type="text" name="search" id="search" placeholder="Nom, email, matricule..."
                           class="block w-full sm:min-w-[200px] rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                           value="{{ $search ?? '' }}">
                </div>

                 {{-- Filtre Statut --}}
                 <div>
                    <label for="status_filter_emp" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Statut</label>
                    <select name="status" id="status_filter_emp" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                        <option value="all" {{ !$statusFilter || $statusFilter == 'all' ? 'selected' : '' }}>Tous</option>
                        {{-- $statuses est passé par le contrôleur --}}
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                 {{-- Filtre Poste --}}
                 <div>
                    <label for="job_title_filter" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Poste</label>
                    <select name="job_title" id="job_title_filter" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                        <option value="">Tous</option>
                         {{-- $jobTitles est passé par le contrôleur --}}
                        @foreach($jobTitles as $title)
                            <option value="{{ $title }}" {{ $jobTitleFilter == $title ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Alternative: Input text pour recherche partielle poste --}}
                    {{-- <input type="text" name="job_title" placeholder="Filtrer poste..." value="{{ $jobTitleFilter ?? '' }}" class="block mt-1 w-full rounded-md ... text-xs"> --}}
                </div>

                 {{-- Bouton Filtrer/Chercher --}}
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Filtrer
                </button>

                 {{-- Bouton Réinitialiser TOUS les filtres --}}
                 @if($search || $statusFilter || $jobTitleFilter)
                    <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" title="Effacer les filtres et la recherche">
                        ↻ {{-- Symbole Recharger/Reset --}}
                    </a>
                @endif
            </form> {{-- Fin Formulaire Filtres/Recherche --}}

             {{-- Bouton Ajouter Employé --}}
            <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('+ Ajouter Employé') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                     {{-- Messages Flash --}}
                    @if (session('success')) <div class="mb-4 bg-green-100 border border-green-400 text-green-700 dark:text-green-200 dark:border-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div> @endif
                    @if (session('error')) <div class="mb-4 bg-red-100 border border-red-400 text-red-700 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div> @endif

                    {{-- Tableau des employés --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nom</th>
                                    <th scope="col" class="px-6 py-3">Matricule</th>
                                    <th scope="col" class="px-6 py-3">Poste</th>
                                    <th scope="col" class="px-6 py-3">Date Embauche</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $employee->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 font-mono">{{ $employee->employee_number ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $employee->job_title ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $employee->hire_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($employee->status == 'active') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                @elseif($employee->status == 'terminated') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif"> {{-- on_leave --}}
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             <a href="{{ route('employees.show', $employee->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir/Modifier</a>
                                            {{-- La suppression est gérée avec précaution dans le contrôleur --}}
                                            {{-- <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Confirmer suppression ?');" class="inline"> @csrf @method('DELETE') <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Suppr.</button> </form> --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Aucun employé trouvé pour les critères sélectionnés.
                                         @if($search || $statusFilter || $jobTitleFilter)
                                             <a href="{{ route('employees.index') }}" class="ml-2 text-sm text-blue-500 hover:underline">(Réinitialiser les filtres)</a>
                                         @endif
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{-- $employees->links() rendra les liens avec les paramètres de filtre --}}
                        {{ $employees->links() }}
                    </div>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>