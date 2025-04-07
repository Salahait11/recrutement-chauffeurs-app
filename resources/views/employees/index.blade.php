{{-- resources/views/employees/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Employés') }}
            </h2>

            {{-- Formulaire de Recherche avec Réinitialiser --}}
            <div class="w-full sm:w-auto flex items-center space-x-2">
                <form method="GET" action="{{ route('employees.index') }}" class="flex">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Rechercher nom, email, matricule..."
                            class="block w-full sm:w-64 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white pe-10"
                            value="{{ $search ?? '' }}">
                        <span class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-500">
                            🔍
                        </span>
                    </div>
                    <button type="submit"
                        class="ml-2 px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition">
                        Rechercher
                    </button>
                </form>

                @if($search)
                    <a href="{{ route('employees.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600 transition">
                        Réinitialiser
                    </a>
                @endif
            </div>

            <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mb-4">
                {{ __('+ Ajouter Employé') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Messages flash --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 text-red-800 px-4 py-2 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

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
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $employee->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">{{ $employee->employee_number ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $employee->job_title ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $employee->hire_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($employee->status == 'active') bg-green-100 text-green-800
                                                @elseif($employee->status == 'terminated') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ route('employees.show', $employee->id) }}" class="font-medium text-blue-600 hover:underline">Voir</a>
                                            <a href="{{ route('employees.edit', $employee->id) }}" class="font-medium text-indigo-600 hover:underline">Modifier</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Aucun employé trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
