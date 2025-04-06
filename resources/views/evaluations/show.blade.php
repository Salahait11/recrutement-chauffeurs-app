{{-- resources/views/evaluations/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails de l\'Évaluation') }}
                @if($evaluation->interview)
                    {{ __('pour l\'entretien de') }}
                    @if($evaluation->candidate)
                        {{ $evaluation->candidate->first_name }} {{ $evaluation->candidate->last_name }}
                    @endif
                    {{ __('du') }} {{ $evaluation->interview->interview_date->format('d/m/Y') }}
                @elseif($evaluation->candidate)
                     {{ __('pour le candidat') }} {{ $evaluation->candidate->first_name }} {{ $evaluation->candidate->last_name }}
                @endif
            </h2>
            {{-- Bouton pour retourner (vers l'entretien ou la liste des évaluations ?) --}}
            @if($evaluation->interview)
            <a href="{{ route('interviews.show', $evaluation->interview->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Retour à l\'Entretien') }}
            </a>
            @else
             <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Retour à la liste') }}
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Afficher les messages flash --}}
                     @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Informations générales --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="md:col-span-1 font-semibold">{{ __('Candidat Évalué') }}</div>
                        <div class="md:col-span-2">{{ $evaluation->candidate->first_name ?? 'N/A' }} {{ $evaluation->candidate->last_name ?? '' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Évaluateur') }}</div>
                        <div class="md:col-span-2">{{ $evaluation->evaluator->name ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Date Évaluation') }}</div>
                        <div class="md:col-span-2">{{ $evaluation->created_at->format('d/m/Y H:i') }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Entretien Associé') }}</div>
                        <div class="md:col-span-2">
                            @if($evaluation->interview)
                                <a href="{{ route('interviews.show', $evaluation->interview->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    Voir l'entretien du {{ $evaluation->interview->interview_date->format('d/m/Y H:i') }}
                                </a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    {{-- Réponses aux Critères --}}
                     <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Détail des Critères</h3>
                     <div class="space-y-4">
                        @forelse($evaluation->responses as $response)
                            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-md">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-medium text-md text-gray-700 dark:text-gray-300">{{ $response->criterion->name ?? 'Critère Inconnu' }}</span>
                                    <span class="font-bold text-lg text-indigo-600 dark:text-indigo-400">{{ $response->rating }} / 5</span>
                                </div>
                                @if($response->comment)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap pl-2 border-l-2 border-gray-300 dark:border-gray-600">{{ $response->comment }}</p>
                                @else
                                     <p class="text-sm text-gray-400 dark:text-gray-500 italic pl-2 border-l-2 border-gray-300 dark:border-gray-600">Aucun commentaire.</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400">Aucune réponse aux critères n'a été enregistrée pour cette évaluation.</p>
                        @endforelse
                     </div>

                    {{-- Conclusion & Recommandation --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                         <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Conclusion Générale</h3>
                         <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1 font-semibold">{{ __('Conclusion') }}</div>
                            <div class="md:col-span-2 whitespace-pre-wrap">{{ $evaluation->conclusion ?? 'Non renseignée' }}</div>

                            <div class="md:col-span-1 font-semibold">{{ __('Recommandation') }}</div>
                            <div class="md:col-span-2">
                                @if($evaluation->recommendation)
                                    <span class="font-semibold
                                        @if($evaluation->recommendation == 'positive') text-green-600 dark:text-green-400 @endif
                                        @if($evaluation->recommendation == 'negative') text-red-600 dark:text-red-400 @endif
                                        @if($evaluation->recommendation == 'neutral') text-yellow-600 dark:text-yellow-400 @endif
                                    ">
                                        {{ ucfirst($evaluation->recommendation) }}
                                    </span>
                                @else
                                    Non renseignée
                                @endif
                            </div>

                            {{-- Note Globale (si utilisée) --}}
                            {{--
                            <div class="md:col-span-1 font-semibold">{{ __('Note Globale') }}</div>
                            <div class="md:col-span-2">{{ $evaluation->overall_rating ?? 'N/A' }}</div>
                            --}}
                         </div>
                    </div>

                     <hr class="dark:border-gray-600 mt-6">

                    {{-- Zone pour les actions sur l'évaluation elle-même (Modifier/Supprimer l'évaluation) --}}
                    <div class="flex justify-end space-x-3 mt-6">
                        {{-- <a href="{{ route('evaluations.edit', $evaluation->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Modifier Évaluation') }}
                        </a> --}}
                        <form method="POST" action="{{ route('evaluations.destroy', $evaluation->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette évaluation ?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Supprimer Évaluation') }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>