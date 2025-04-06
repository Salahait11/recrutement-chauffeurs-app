{{-- resources/views/offers/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails de l\'Offre #') }}{{ $offer->id }}
                 @if($offer->candidate)
                    {{ __('pour') }} {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}
                 @endif
            </h2>
             {{-- Bouton Retour à la liste --}}
             <a href="{{ route('offers.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
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
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Détails de l'offre --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2"> {{-- Ajusté gap-y --}}
                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('ID Offre') }}</div>
                        <div class="md:col-span-2">{{ $offer->id }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Candidat') }}</div>
                        <div class="md:col-span-2">
                             @if($offer->candidate)
                                <a href="{{ route('candidates.show', $offer->candidate->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Poste Proposé') }}</div>
                        <div class="md:col-span-2">{{ $offer->position_offered }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Type de Contrat') }}</div>
                        <div class="md:col-span-2">{{ $offer->contract_type ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Date de Début Prévue') }}</div>
                        <div class="md:col-span-2">{{ $offer->start_date ? $offer->start_date->format('d/m/Y') : 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Salaire') }}</div>
                        <div class="md:col-span-2">{{ $offer->salary ? number_format($offer->salary, 2, ',', ' ') . ' €' : 'N/A' }} {{ $offer->salary_period ? '(' . $offer->salary_period . ')' : '' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400 pt-1">{{ __('Avantages') }}</div>
                        <div class="md:col-span-2 whitespace-pre-wrap bg-gray-50 dark:bg-gray-900 p-2 rounded border border-gray-200 dark:border-gray-700 text-sm">{{ $offer->benefits ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400 pt-1">{{ __('Conditions Particulières') }}</div>
                        <div class="md:col-span-2 whitespace-pre-wrap bg-gray-50 dark:bg-gray-900 p-2 rounded border border-gray-200 dark:border-gray-700 text-sm">{{ $offer->specific_conditions ?? 'N/A' }}</div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Statut') }}</div>
                        <div class="md:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @switch($offer->status)
                                    @case('accepted') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break
                                    @case('rejected')
                                    @case('expired')
                                    @case('withdrawn') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                    @case('sent') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @break
                                    @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 {{-- draft --}}
                                @endswitch
                            ">
                                {{ ucfirst($offer->status) }}
                            </span>
                        </div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Créée par') }}</div>
                        <div class="md:col-span-2">{{ $offer->creator->name ?? 'N/A' }} le {{ $offer->created_at->format('d/m/Y H:i') }}</div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Envoyée le') }}</div>
                        <div class="md:col-span-2">{{ $offer->sent_at ? $offer->sent_at->format('d/m/Y H:i') : 'Pas encore envoyée' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Réponse du Candidat le') }}</div>
                        <div class="md:col-span-2">{{ $offer->responded_at ? $offer->responded_at->format('d/m/Y H:i') : 'Pas de réponse enregistrée' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Expire le') }}</div>
                        <div class="md:col-span-2">{{ $offer->expires_at ? $offer->expires_at->format('d/m/Y') : 'N/A' }}</div>

                    </div>

                     {{-- Affichage du texte complet de l'offre si présent --}}
                    @if($offer->offer_text)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                         <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Texte de l'Offre Enregistré</h3>
                         <div class="prose dark:prose-invert max-w-none p-4 bg-gray-50 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 text-sm">
                            {!! nl2br(e($offer->offer_text)) !!} {{-- nl2br pour les sauts de ligne, e() pour échapper --}}
                         </div>
                    </div>
                    @endif


                    <hr class="dark:border-gray-600 mt-6">

                    {{-- Actions possibles sur l'offre --}}
                    <div class="flex justify-end space-x-3 mt-6">

                         {{-- Boutons pour changer le statut (si applicable) --}}
                        @if($offer->status === 'sent')
                            {{-- Formulaire pour marquer comme Acceptée --}}
                            <form method="POST" action="{{ route('offers.update', $offer->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_action" value="accept"> {{-- Champ pour identifier l'action --}}
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Marquer Acceptée
                                </button>
                            </form>
                            {{-- Formulaire pour marquer comme Refusée --}}
                             <form method="POST" action="{{ route('offers.update', $offer->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_action" value="reject"> {{-- Champ pour identifier l'action --}}
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-400 active:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Marquer Refusée
                                </button>
                            </form>
                        @endif

                         {{-- Bouton Modifier (toujours utile ?) --}}
                         <a href="{{ route('offers.edit', $offer->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Modifier') }}
                         </a>
                         {{-- Bouton Supprimer --}}
                         <form method="POST" action="{{ route('offers.destroy', $offer->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');" class="inline">
                             @csrf @method('DELETE')
                             <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                 {{ __('Supprimer') }}
                             </button>
                         </form>
                    </div>
                 </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w-7xl --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>