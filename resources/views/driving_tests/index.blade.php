{{-- resources/views/driving_tests/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Tests de Conduite') }}
            </h2>
            {{-- Bouton pour planifier un test --}}
            <a href="{{ route('driving-tests.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Planifier un Test') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Afficher les messages flash --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif

                    {{-- Tableau pour afficher les tests --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Candidat</th>
                                    <th scope="col" class="px-6 py-3">Date & Heure</th>
                                    <th scope="col" class="px-6 py-3">Véhicule</th>
                                    <th scope="col" class="px-6 py-3">Évaluateur</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">Résultat</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($drivingTests as $test)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        {{-- Nom du candidat --}}
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            @if($test->candidate)
                                                <a href="{{ route('candidates.show', $test->candidate->id) }}" class="hover:underline">
                                                    {{ $test->candidate->first_name }} {{ $test->candidate->last_name }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 italic">Candidat supprimé</span>
                                            @endif
                                        </td>
                                        {{-- Date et Heure --}}
                                        <td class="px-6 py-4">
                                            {{ $test->test_date->format('d/m/Y H:i') }}
                                        </td>
                                        {{-- Véhicule --}}
                                        <td class="px-6 py-4">
                                            {{ $test->vehicle ? ($test->vehicle->brand.' '.$test->vehicle->model.' ('.$test->vehicle->plate_number.')') : 'N/A' }}
                                        </td>
                                        {{-- Évaluateur --}}
                                        <td class="px-6 py-4">
                                            {{ $test->evaluator->name ?? 'N/A' }}
                                        </td>
                                        {{-- Statut --}}
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @switch($test->status)
                                                    @case('completed') bg-green-100 text-green-800 @break
                                                    @case('canceled') bg-red-100 text-red-800 @break
                                                    @default bg-blue-100 text-blue-800 {{-- scheduled --}}
                                                @endswitch
                                            ">
                                                {{ ucfirst($test->status) }}
                                            </span>
                                        </td>
                                         {{-- Résultat (Passed) --}}
                                         <td class="px-6 py-4">
                                             @if($test->status === 'completed')
                                                 @if($test->passed === true)
                                                     <span class="text-green-600 font-semibold">Réussi</span>
                                                 @elseif($test->passed === false)
                                                      <span class="text-red-600 font-semibold">Échoué</span>
                                                 @else
                                                      <span class="text-gray-500 italic">Non évalué</span>
                                                 @endif
                                             @else
                                                -
                                             @endif
                                         </td>
                                        {{-- Actions --}}
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                            {{-- Lien vers détails du test --}}
                                            <a href="{{ route('driving-tests.show', $test->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir</a>
                                            {{-- Lien vers modification --}}
                                            <a href="{{ route('driving-tests.edit', $test->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                            {{-- Bouton Supprimer --}}
                                            <form action="{{ route('driving-tests.destroy', $test->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler/supprimer ce test ?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button>
                                            </form>
                                             {{-- Ajouter lien vers Évaluation du test plus tard --}}
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Message si aucun test trouvé --}}
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Aucun test de conduite planifié pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Liens de pagination --}}
                    <div class="mt-4">
                        {{ $drivingTests->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>