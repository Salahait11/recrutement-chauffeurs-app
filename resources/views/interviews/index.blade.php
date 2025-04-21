<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Liste des Entretiens') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('interviews.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Créer un nouvel entretien') }}
                    </a>

                    <table class="min-w-full divide-y divide-gray-200 mt-4">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            {{-- @forelse($interviews as $interview) --}}
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('interviews.edit', 1) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-2">{{ __('Modifier') }}</a>
                                        <form action="{{ route('interviews.destroy', 1) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">{{ __('Supprimer') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            {{-- @empty --}}
                            {{-- @endforelse --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>