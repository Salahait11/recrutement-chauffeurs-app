{{-- resources/views/employees/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Employés') }}
            </h2>
            {{-- Bouton pour ajouter un employé manuellement ? Ou seulement via conversion ? --}}
            {{-- <a href="{{ route('employees.create') }}" class="inline-flex items-center ...">+ Ajouter Employé</a> --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                     {{-- Messages flash --}}
                    @if (session('success')) <div class="mb-4 bg-green-100 ...">{{ session('success') }}</div> @endif
                    @if (session('error')) <div class="mb-4 bg-red-100 ...">{{ session('error') }}</div> @endif

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left ...">
                            <thead class="text-xs text-gray-700 uppercase ...">
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
                                    <tr class="bg-white border-b dark:bg-gray-800 ...">
                                        <td class="px-6 py-4 font-medium ...">{{ $employee->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $employee->employee_number ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $employee->job_title ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $employee->hire_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs ... rounded-full
                                                @if($employee->status == 'active') bg-green-100 text-green-800 @elseif($employee->status == 'terminated') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             <a href="{{ route('employees.show', $employee->id) }}" class="font-medium text-blue-600 ...">Voir</a>
                                            <a href="{{ route('employees.edit', $employee->id) }}" class="font-medium text-indigo-600 ...">Modifier</a>
                                            {{-- La suppression est sensible, peut-être juste désactiver --}}
                                            {{-- <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" ...>@csrf @method('DELETE')<button>Suppr.</button></form> --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-4 text-center ...">Aucun employé trouvé.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $employees->links() }}</div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>