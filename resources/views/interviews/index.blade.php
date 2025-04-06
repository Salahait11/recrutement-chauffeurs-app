{{-- resources/views/interviews/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des Entretiens Planifiés') }}
            </h2>
             {{-- Bouton pour planifier un entretien --}}
            <a href="{{ route('interviews.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Planifier un Entretien') }}
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
                     @if (session('info')) {{-- Pour les messages placeholder --}}
                        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif

                    {{-- Tableau pour afficher les entretiens --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Candidat</th>
                                    <th scope="col" class="px-6 py-3">Date & Heure</th>
                                    <th scope="col" class="px-6 py-3">Type</th>
                                    <th scope="col" class="px-6 py-3">Intervieweur</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($interviews as $interview)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        {{-- Nom du candidat (avec lien vers sa page de détails) --}}
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            @if($interview->candidate)
                                                <a href="{{ route('candidates.show', $interview->candidate->id) }}" class="hover:underline">
                                                    {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 italic">Candidat supprimé</span>
                                            @endif
                                        </td>
                                        {{-- Date et Heure (formatée) --}}
                                        <td class="px-6 py-4">
                                            {{ $interview->interview_date->format('d/m/Y H:i') }}
                                        </td>
                                        {{-- Type d'entretien --}}
                                        <td class="px-6 py-4">
                                            {{ $interview->type ?? 'N/A' }}
                                        </td>
                                        {{-- Interviewer --}}
                                        <td class="px-6 py-4">
                                            {{ $interview->interviewer->name ?? 'N/A' }}
                                        </td>
                                        {{-- Statut --}}
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @switch($interview->status)
                                                    @case('completed') bg-green-100 text-green-800 @break
                                                    @case('canceled') bg-red-100 text-red-800 @break
                                                    @case('rescheduled') bg-yellow-100 text-yellow-800 @break
                                                    @default bg-blue-100 text-blue-800 {{-- scheduled --}}
                                                @endswitch
                                            ">
                                                {{ ucfirst($interview->status) }}
                                            </span>
                                        </td>
                                        {{-- Actions --}}
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             <a href="{{ route('interviews.show', $interview->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir</a>
                                            <a href="{{ route('interviews.edit', $interview->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                            {{-- Bouton Supprimer dans un formulaire --}}
                                            <form action="{{ route('interviews.destroy', $interview->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler/supprimer cet entretien ?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Message si aucun entretien trouvé --}}
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Aucun entretien planifié pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Affichage des liens de pagination --}}
                    <div class="mt-4">
                        {{ $interviews->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>