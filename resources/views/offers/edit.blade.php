{{-- resources/views/offers/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier l\'Offre #') }}{{ $offer->id }}
             @if($offer->candidate)
                {{ __('pour') }} {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}
             @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Afficher les erreurs de validation --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 ..." role="alert"> ... </div>
                    @endif

                    {{-- Formulaire d'édition --}}
                    <form method="POST" action="{{ route('offers.update', $offer->id) }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="candidate_id" value="{{ $offer->candidate_id }}"> {{-- L'ID candidat n'est pas modifiable ici --}}
                        <p class="mb-6 text-lg font-semibold border-b pb-2 border-gray-300 dark:border-gray-600">
                            Offre pour : <span class="text-indigo-600 dark:text-indigo-400">{{ $offer->candidate->first_name ?? 'N/A' }} {{ $offer->candidate->last_name ?? '' }}</span>
                         </p>

                        <div class="space-y-6">
                            {{-- Poste Proposé --}}
                            <div>
                                <label for="position_offered" class="block font-medium text-sm ...">{{ __('Poste Proposé') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="position_offered" id="position_offered" class="block mt-1 w-full rounded-md ..." value="{{ old('position_offered', $offer->position_offered) }}" required>
                                @error('position_offered') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                            {{-- Type Contrat & Date Début --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contract_type" class="block font-medium text-sm ...">{{ __('Type de Contrat') }}</label>
                                    <input type="text" name="contract_type" id="contract_type" class="block mt-1 w-full rounded-md ..." value="{{ old('contract_type', $offer->contract_type) }}">
                                    @error('contract_type') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="start_date" class="block font-medium text-sm ...">{{ __('Date de Début Souhaitée') }}</label>
                                    <input type="date" name="start_date" id="start_date" class="block mt-1 w-full rounded-md ..." value="{{ old('start_date', optional($offer->start_date)->format('Y-m-d')) }}">
                                    @error('start_date') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                            </div>

                             {{-- Salaire & Période --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="salary" class="block font-medium text-sm ...">{{ __('Rémunération Proposée') }}</label>
                                    <input type="number" step="0.01" name="salary" id="salary" class="block mt-1 w-full rounded-md ..." value="{{ old('salary', $offer->salary) }}">
                                     @error('salary') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="salary_period" class="block font-medium text-sm ...">{{ __('Périodicité Salaire') }}</label>
                                    <select name="salary_period" id="salary_period" class="block mt-1 w-full rounded-md ...">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="Annuel" {{ old('salary_period', $offer->salary_period) == 'Annuel' ? 'selected' : '' }}>Annuel</option>
                                        <option value="Mensuel" {{ old('salary_period', $offer->salary_period) == 'Mensuel' ? 'selected' : '' }}>Mensuel</option>
                                        <option value="Horaire" {{ old('salary_period', $offer->salary_period) == 'Horaire' ? 'selected' : '' }}>Horaire</option>
                                    </select>
                                     @error('salary_period') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Avantages --}}
                            <div>
                                <label for="benefits" class="block font-medium text-sm ...">{{ __('Avantages') }}</label>
                                <textarea name="benefits" id="benefits" rows="3" class="block mt-1 w-full rounded-md ...">{{ old('benefits', $offer->benefits) }}</textarea>
                                 @error('benefits') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                            {{-- Conditions Particulières --}}
                            <div>
                                <label for="specific_conditions" class="block font-medium text-sm ...">{{ __('Conditions Particulières') }}</label>
                                <textarea name="specific_conditions" id="specific_conditions" rows="3" class="block mt-1 w-full rounded-md ...">{{ old('specific_conditions', $offer->specific_conditions) }}</textarea>
                                 @error('specific_conditions') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                             {{-- Date d'Expiration Offre --}}
                            <div>
                                <label for="expires_at" class="block font-medium text-sm ...">{{ __('Date d\'expiration de l\'offre') }}</label>
                                <input type="date" name="expires_at" id="expires_at" class="block mt-1 w-full rounded-md ..." value="{{ old('expires_at', optional($offer->expires_at)->format('Y-m-d')) }}">
                                @error('expires_at') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                            {{-- Statut (Modifiable ?) --}}
                            {{-- On pourrait ajouter un select pour changer le statut ici,
                                 mais les actions dédiées (Accepter/Refuser) sont peut-être mieux --}}

                        </div>

                        {{-- Boutons --}}
                        <div class="flex items-center justify-end mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                             <a href="{{ route('offers.show', $offer->id) }}" class="underline text-sm ... mr-4">{{ __('Annuler') }}</a>
                             <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 ...">
                                {{ __('Mettre à Jour l\'Offre') }}
                             </button>
                         </div>
                    </form>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>