{{-- resources/views/interviews/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails de l\'Entretien') }}
                @if($interview->candidate)
                     {{ __('pour') }} {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                @endif
            </h2>
            {{-- Bouton pour retourner à la liste --}}
            <a href="{{ route('interviews.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Afficher les messages flash --}}
                     @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    {{-- Ajouter ici pour session('error') si besoin --}}

                    {{-- Détails de l'entretien --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1 font-semibold">{{ __('Candidat') }}</div>
                        <div class="md:col-span-2">
                            @if($interview->candidate)
                                <a href="{{ route('candidates.show', $interview->candidate->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                                </a>
                            @else
                                <span class="italic text-gray-500">Candidat non trouvé</span>
                            @endif
                        </div>

                        <div class="md:col-span-1 font-semibold">{{ __('Date et Heure') }}</div>
                        <div class="md:col-span-2">{{ $interview->interview_date->format('d/m/Y H:i') }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Type') }}</div>
                        <div class="md:col-span-2">{{ $interview->type ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Lieu / Lien') }}</div>
                        <div class="md:col-span-2">{{ $interview->location ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Intervieweur Principal') }}</div>
                        <div class="md:col-span-2">{{ $interview->interviewer->name ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Planifié par') }}</div>
                        <div class="md:col-span-2">{{ $interview->scheduler->name ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Statut') }}</div>
                        <div class="md:col-span-2">
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
                        </div>

                         <div class="md:col-span-1 font-semibold">{{ __('Notes Préparation') }}</div>
                        <div class="md:col-span-2 whitespace-pre-wrap">{{ $interview->notes ?? 'Aucune note' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Compte-rendu / Feedback') }}</div>
                        <div class="md:col-span-2 whitespace-pre-wrap">{{ $interview->feedback ?? 'Non renseigné' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Date de Planification') }}</div>
                        <div class="md:col-span-2">{{ $interview->created_at->format('d/m/Y H:i') }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Dernière Mise à Jour') }}</div>
                        <div class="md:col-span-2">{{ $interview->updated_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <hr class="dark:border-gray-600">

                    {{-- Zone pour les actions --}}
                    <div class="flex justify-between items-center">
                        {{-- Bouton Évaluer --}}
                        <div>
                            {{-- On affiche le bouton Evaluer seulement si l'entretien n'est pas déjà évalué et s'il est 'completed' ou 'scheduled' ? --}}
                            {{-- @if(! $interview->evaluations()->exists() && in_array($interview->status, ['scheduled', 'completed'])) --}}
                                <a href="{{ route('interviews.evaluations.create', $interview->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Évaluer cet Entretien
                                </a>
                            {{-- @endif --}}
                            {{-- Afficher un lien vers l'évaluation si elle existe déjà ? --}}
                             @if($interview->evaluations()->exists())
                                <a href="{{ route('evaluations.show', $interview->evaluations()->first()->id) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Voir l'Évaluation
                                </a>
                            @endif
                        </div>

                        {{-- Boutons Modifier et Supprimer --}}
                        <div class="flex space-x-3">
                             <a href="{{ route('interviews.edit', $interview->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Modifier') }}
                            </a>
                            <form method="POST" action="{{ route('interviews.destroy', $interview->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet entretien ?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Supprimer') }}
                                </button>
                            </form>
                        </div>
                    </div>

                     {{-- Afficher les évaluations associées ? --}}
                    {{-- @if($interview->evaluations->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Évaluations') }}
                            </h3>
                            <ul>
                                @foreach($interview->evaluations as $evaluation)
                                    <li><a href="{{ route('evaluations.show', $evaluation->id) }}">Évaluation par {{ $evaluation->evaluator->name ?? 'N/A' }} le {{ $evaluation->created_at->format('d/m/Y') }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}


                </div>
            </div>
        </div>
    </div>
</x-app-layout>