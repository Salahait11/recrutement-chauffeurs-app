{{-- resources/views/offers/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
             {{-- Le titre dépend si on passe un candidat ou non --}}
            @isset($candidate)
                {{ __('Créer une Offre pour :') }} {{ $candidate->first_name }} {{ $candidate->last_name }}
            @else
                 {{ __('Créer une Nouvelle Offre') }}
            @endisset
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Afficher les erreurs de validation --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oups!</strong>
                            <span class="block sm:inline">Il y a eu des problèmes avec votre saisie.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('offers.store') }}">
                        @csrf

                        {{-- Champ caché pour le candidat (si fourni par la route createForCandidate) --}}
                        @isset($candidate)
                            <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                             <p class="mb-6 text-lg font-semibold border-b pb-2 border-gray-300 dark:border-gray-600">
                                Offre pour : <span class="text-indigo-600 dark:text-indigo-400">{{ $candidate->first_name }} {{ $candidate->last_name }}</span>
                             </p>
                        @else
                            {{-- Si on arrive via offers.create, il faut pouvoir choisir le candidat --}}
                             <div class="mb-6 border-b pb-6 border-gray-300 dark:border-gray-600">
                                <label for="candidate_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Candidat Concerné') }} <span class="text-red-500">*</span></label>
                                <select name="candidate_id" id="candidate_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">-- Sélectionner un candidat --</option>
                                    {{-- Assurez-vous que $candidates est passé par le contrôleur si cette route est utilisée --}}
                                    @foreach($candidates ?? [] as $c)
                                        <option value="{{ $c->id }}" {{ old('candidate_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->first_name }} {{ $c->last_name }} (ID: {{ $c->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('candidate_id') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endisset

                        <div class="space-y-6">
                            {{-- Poste Proposé --}}
                            <div>
                                <label for="position_offered" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Poste Proposé') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="position_offered" id="position_offered" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('position_offered') }}" required>
                                @error('position_offered') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Type de Contrat --}}
                                <div>
                                    <label for="contract_type" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Type de Contrat') }}</label>
                                    <input type="text" name="contract_type" id="contract_type" placeholder="Ex: CDI, CDD 6 mois..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('contract_type') }}">
                                     @error('contract_type') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Date de Début --}}
                                <div>
                                    <label for="start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date de Début Souhaitée') }}</label>
                                    <input type="date" name="start_date" id="start_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('start_date') }}">
                                    @error('start_date') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                 {{-- Salaire --}}
                                <div>
                                    <label for="salary" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Rémunération Proposée') }}</label>
                                    <input type="number" step="0.01" name="salary" id="salary" placeholder="Ex: 24000.00" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('salary') }}">
                                     @error('salary') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                                 {{-- Période Salaire --}}
                                <div>
                                    <label for="salary_period" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Périodicité Salaire') }}</label>
                                    <select name="salary_period" id="salary_period" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="Annuel" {{ old('salary_period') == 'Annuel' ? 'selected' : '' }}>Annuel</option>
                                        <option value="Mensuel" {{ old('salary_period') == 'Mensuel' ? 'selected' : '' }}>Mensuel</option>
                                        <option value="Horaire" {{ old('salary_period') == 'Horaire' ? 'selected' : '' }}>Horaire</option>
                                    </select>
                                     @error('salary_period') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Avantages --}}
                            <div>
                                <label for="benefits" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Avantages') }}</label>
                                <textarea name="benefits" id="benefits" rows="3" placeholder="Ex: Mutuelle, Tickets restaurant, Véhicule de fonction..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('benefits') }}</textarea>
                                 @error('benefits') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Conditions Particulières --}}
                            <div>
                                <label for="specific_conditions" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Conditions Particulières') }}</label>
                                <textarea name="specific_conditions" id="specific_conditions" rows="3" placeholder="Ex: Période d'essai, Clause de mobilité..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('specific_conditions') }}</textarea>
                                 @error('specific_conditions') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                             {{-- Date d'Expiration Offre --}}
                            <div>
                                <label for="expires_at" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date d\'expiration de l\'offre') }}</label>
                                <input type="date" name="expires_at" id="expires_at" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('expires_at') }}">
                                @error('expires_at') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                             {{-- Contenu Offre (Optionnel) --}}
                            {{--
                            <div>
                                <label for="offer_text" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Texte de l\'offre (pour email)') }}</label>
                                <textarea name="offer_text" id="offer_text" rows="10" placeholder="Rédigez ici le corps de l'offre qui pourrait être envoyée..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('offer_text') }}</textarea>
                                @error('offer_text') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                             --}}

                        </div>

                         {{-- Boutons --}}
                        <div class="flex items-center justify-end mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                             <a href="{{ isset($candidate) ? route('candidates.show', $candidate->id) : route('offers.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Annuler') }}
                             </a>
                             {{-- Bouton Brouillon --}}
                             <button type="submit" name="status" value="draft" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                                {{ __('Enregistrer Brouillon') }}
                             </button>
                             {{-- Bouton Marquer Envoyée --}}
                             <button type="submit" name="status" value="sent" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                 {{ __('Enregistrer et Marquer Envoyée') }}
                             </button>
                         </div>

                    </form>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w-4xl --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>