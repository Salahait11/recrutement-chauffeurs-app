{{-- resources/views/driving_tests/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Planifier un Nouveau Test de Conduite') }}
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

                    {{-- Formulaire de planification --}}
                    <form method="POST" action="{{ route('driving-tests.store') }}">
                        @csrf

                        <div class="space-y-6">
                            {{-- Candidat --}}
                            <div>
                                <label for="candidate_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Candidat') }} <span class="text-red-500">*</span></label>
                                <select name="candidate_id" id="candidate_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">-- Sélectionner un candidat --</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                            {{ $candidate->first_name }} {{ $candidate->last_name }} (ID: {{ $candidate->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                             {{-- Véhicule --}}
                            <div>
                                 <label for="vehicle_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Véhicule') }} <span class="text-red-500">*</span></label>
                                 <select name="vehicle_id" id="vehicle_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                     <option value="">-- Sélectionner un véhicule --</option>
                                     @foreach($vehicles as $vehicle)
                                         <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                             {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})
                                         </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Date et Heure du Test --}}
                            <div>
                                <label for="test_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date et Heure du Test') }} <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="test_date" id="test_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('test_date') }}" required>
                            </div>

                            {{-- Itinéraire / Détails --}}
                            <div>
                                <label for="route_details" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Itinéraire / Conditions du Test') }}</label>
                                <textarea name="route_details" id="route_details" rows="4" placeholder="Décrire l'itinéraire prévu, les manœuvres spécifiques, conditions météo..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('route_details') }}</textarea>
                            </div>

                        </div>

                        {{-- Boutons Annuler et Enregistrer --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('driving-tests.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Planifier le Test') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>