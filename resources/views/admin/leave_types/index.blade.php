{{-- resources/views/leave_types/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestion des Types de Congé') }}
            </h2>
            <a href="{{ route('admin.leave-types.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('+ Nouveau Type') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Messages Flash --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 dark:bg-green-900 dark:text-green-200 dark:border-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nom</th>
                                    <th scope="col" class="px-6 py-3">Approbation Requise</th>
                                    <th scope="col" class="px-6 py-3">Affecte Solde</th>
                                    <th scope="col" class="px-6 py-3">Actif</th>
                                    <th scope="col" class="px-6 py-3">Couleur</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaveTypes as $leaveType)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $leaveType->name }}</td>
                                        <td class="px-6 py-4">{{ $leaveType->requires_approval ? 'Oui' : 'Non' }}</td>
                                        <td class="px-6 py-4">{{ $leaveType->affects_balance ? 'Oui' : 'Non' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $leaveType->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                {{ $leaveType->is_active ? 'Oui' : 'Non' }}
                                            </span>
                                        </td>
                                         <td class="px-6 py-4">
                                             @if($leaveType->color_code)
                                                <span class="inline-block w-4 h-4 rounded-full border border-gray-300 dark:border-gray-600" style="background-color: {{ $leaveType->color_code }}"></span>
                                                <span class="ml-1 text-xs font-mono">{{ $leaveType->color_code }}</span>
                                             @else
                                                -
                                             @endif
                                         </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             <a href="{{ route('admin.leave-types.edit', $leaveType->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                             <form action="{{ route('admin.leave-types.destroy', $leaveType->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type ? (Possible seulement s\'il n\'est pas utilisé)');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Aucun type de congé défini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $leaveTypes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>