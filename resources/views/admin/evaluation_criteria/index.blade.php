{{-- resources/views/admin/evaluation_criteria/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestion des Critères d\'Évaluation') }}
            </h2>
            <a href="{{ route('admin.evaluation-criteria.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('+ Nouveau Critère') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Messages Flash --}}
                    @if (session('success'))<div class="mb-4 bg-green-100 ...">{{ session('success') }}</div>@endif
                    @if (session('error'))<div class="mb-4 bg-red-100 ...">{{ session('error') }}</div>@endif

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nom du Critère</th>
                                    <th scope="col" class="px-6 py-3">Catégorie</th>
                                    <th scope="col" class="px-6 py-3">Description</th>
                                    <th scope="col" class="px-6 py-3">Actif</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($criteria as $criterion)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $criterion->name }}</td>
                                        <td class="px-6 py-4">{{ $criterion->category ?? '-' }}</td>
                                        <td class="px-6 py-4 text-xs">{{ Str::limit($criterion->description, 50) }}</td> {{-- Limite la longueur pour l'affichage --}}
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $criterion->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                {{ $criterion->is_active ? 'Oui' : 'Non' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             <a href="{{ route('admin.evaluation-criteria.edit', $criterion->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                             <form action="{{ route('admin.evaluation-criteria.destroy', $criterion->id) }}" method="POST" onsubmit="return confirm('Supprimer ce critère ? (Seulement si non utilisé)');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Aucun critère d'évaluation défini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $criteria->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>