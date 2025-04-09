{{-- resources/views/offers/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
         <div class="flex flex-wrap justify-between items-center gap-2"> {{-- flex-wrap et gap --}}
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails de l\'Offre #') }}{{ $offer->id }}
                 @if($offer->candidate)
                    {{ __('pour') }} {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}
                 @endif
            </h2>
             <a href="{{ route('offers.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Retour à la liste') }}
             </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                     {{-- Messages Flash --}}
                     @if (session('success')) <div class="mb-4 bg-green-100 border border-green-400 text-green-700 dark:text-green-200 dark:border-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div> @endif
                     @if (session('error')) <div class="mb-4 bg-red-100 border border-red-400 text-red-700 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative" role="alert">{{ session('error') }}</div> @endif
                     @if (session('warning')) <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 dark:text-yellow-200 dark:border-yellow-700 px-4 py-3 rounded relative" role="alert">{{ session('warning') }}</div> @endif


                    {{-- Détails de l'offre --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('ID Offre') }}</div>
                        <div class="md:col-span-2">{{ $offer->id }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Candidat') }}</div>
                        <div class="md:col-span-2">
                             @if($offer->candidate)
                                <a href="{{ route('candidates.show', $offer->candidate->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}
                                </a>
                            @else
                                <span class="text-gray-500 italic">Candidat supprimé</span>
                            @endif
                        </div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Poste Proposé') }}</div>
                        <div class="md:col-span-2 font-medium">{{ $offer->position_offered }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Type de Contrat') }}</div>
                        <div class="md:col-span-2">{{ $offer->contract_type ?? '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Date de Début Prévue') }}</div>
                        <div class="md:col-span-2">{{ $offer->start_date ? $offer->start_date->format('d/m/Y') : '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Salaire') }}</div>
                        <div class="md:col-span-2">{{ $offer->salary ? number_format($offer->salary, 2, ',', ' ') . ' €' : '-' }} {{ $offer->salary_period ? '(' . $offer->salary_period . ')' : '' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400 pt-1">{{ __('Avantages') }}</div>
                        <div class="md:col-span-2 whitespace-pre-wrap bg-gray-50 dark:bg-gray-900 p-2 rounded border border-gray-200 dark:border-gray-700 text-sm">{{ $offer->benefits ?: '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400 pt-1">{{ __('Conditions Particulières') }}</div>
                        <div class="md:col-span-2 whitespace-pre-wrap bg-gray-50 dark:bg-gray-900 p-2 rounded border border-gray-200 dark:border-gray-700 text-sm">{{ $offer->specific_conditions ?: '-' }}</div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Statut') }}</div>
                        <div class="md:col-span-2">
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full
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
                        <div class="md:col-span-2">{{ $offer->creator->name ?? 'N/A' }} <span class="text-xs text-gray-500">le {{ $offer->created_at->format('d/m/Y H:i') }}</span></div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Envoyée le') }}</div>
                        <div class="md:col-span-2">{{ $offer->sent_at ? $offer->sent_at->format('d/m/Y H:i') : '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Réponse le') }}</div>
                        <div class="md:col-span-2">{{ $offer->responded_at ? $offer->responded_at->format('d/m/Y H:i') : '-' }}</div>

                        <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Expire le') }}</div>
                        <div class="md:col-span-2">{{ $offer->expires_at ? $offer->expires_at->format('d/m/Y') : '-' }}</div>
                    </div>

                     {{-- Affichage du texte complet de l'offre si présent --}}
                    @if($offer->offer_text)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                         <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Texte de l'Offre Enregistré</h3>
                         <div class="prose dark:prose-invert max-w-none p-4 bg-gray-50 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 text-sm">
                            {!! nl2br(e($offer->offer_text)) !!}
                         </div>
                    </div>
                    @endif

                    {{-- Actions sur l'offre --}}
                    <hr class="dark:border-gray-700 mt-6">
                    <div class="flex flex-wrap justify-end items-center gap-3 mt-6">

                         {{-- Bouton Télécharger PDF --}}
                         <a href="{{ route('offers.pdf', $offer->id) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                             <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" > <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /> </svg>
                             PDF
                         </a>

                         {{-- Boutons Marquer Acceptée/Refusée (si statut = sent) --}}
                        @if($offer->status === 'sent')
                            <form method="POST" action="{{ route('offers.update', $offer->id) }}"> @csrf @method('PUT') <input type="hidden" name="status_action" value="accept"> <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Marquer Acceptée</button> </form>
                             <form method="POST" action="{{ route('offers.update', $offer->id) }}"> @csrf @method('PUT') <input type="hidden" name="status_action" value="reject"> <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-400 active:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Marquer Refusée</button> </form>
                        @endif

                         {{-- Bouton Modifier --}}
                         <a href="{{ route('offers.edit', $offer->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{ __('Modifier') }}</a>

                         {{-- Bouton Supprimer --}}
                         <form method="POST" action="{{ route('offers.destroy', $offer->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');" class="inline"> @csrf @method('DELETE') <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{ __('Supprimer') }}</button> </form>

                    </div>
                 </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>