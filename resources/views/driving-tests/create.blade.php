{{-- resources/views/driving-tests/create.blade.php --}}
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

                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oups!</strong>
                            <span class="block sm:inline">{{ __('Il y a eu des problèmes avec votre saisie.') }}</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                     {{-- Display Session Feedback (Success/Error) --}}
                     @if (session('success'))
                         <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                             {{ session('success') }}
                         </div>
                     @endif
                     @if (session('error'))
                         <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                             {{ session('error') }}
                         </div>
                     @endif
                      {{-- Add warnings if lists are empty --}}
                    @if ($candidates->isEmpty())
                         <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                            {{ __('Attention: Aucun candidat éligible (statut "test") trouvé pour planifier un test.') }}
                        </div>
                    @endif
                     @if ($vehicles->isEmpty())
                         <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                             {{ __('Attention: Aucun véhicule disponible trouvé.') }}
                         </div>
                     @endif
                     @if ($admins->isEmpty())
                         <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                             {{ __('Attention: Aucun utilisateur avec le rôle "Admin" trouvé pour être examinateur.') }}
                         </div>
                     @endif


                    {{-- Planning Form --}}
                    <form method="POST" action="{{ route('driving-tests.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Candidate --}}
                            <div>
                                <label for="candidate_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Candidat') }} <span class="text-red-500">*</span></label>
                                <select name="candidate_id" id="candidate_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required {{ $candidates->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">-- {{ __('Sélectionner un candidat') }} --</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                            {{ $candidate->first_name }} {{ $candidate->last_name }} (ID: {{ $candidate->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('candidate_id') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                             {{-- Interviewer (Admin) --}}
                             <div>
                                 <label for="interviewer_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Examinateur (Admin)') }} <span class="text-red-500">*</span></label>
                                 <select name="interviewer_id" id="interviewer_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required {{ $admins->isEmpty() ? 'disabled' : '' }}>
                                     <option value="">-- {{ __('Sélectionner un examinateur') }} --</option>
                                     @foreach($admins as $admin)
                                         <option value="{{ $admin->id }}" {{ old('interviewer_id') == $admin->id ? 'selected' : '' }}>
                                             {{ $admin->name }} (ID: {{ $admin->id }})
                                         </option>
                                     @endforeach
                                 </select>
                                  @error('interviewer_id') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                             </div>

                             {{-- Vehicle --}}
                            <div>
                                 <label for="vehicle_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Véhicule') }} <span class="text-red-500">*</span></label>
                                 <select name="vehicle_id" id="vehicle_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required {{ $vehicles->isEmpty() ? 'disabled' : '' }}>
                                     <option value="">-- {{ __('Sélectionner un véhicule') }} --</option>
                                     @foreach($vehicles as $vehicle)
                                         <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                             {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})
                                         </option>
                                    @endforeach
                                </select>
                                 @error('vehicle_id') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Test Date and Time --}}
                            <div>
                                <label for="test_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date et Heure du Test') }} <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="test_date" id="test_date"
                                       class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                       value="{{ old('test_date', now()->format('Y-m-d\TH:i')) }}"
                                       min="{{ now()->format('Y-m-d\TH:i') }}" required>
                                 @error('test_date') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Notes (formerly Route Details) --}}
                            <div class="md:col-span-2">
                                <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Notes / Itinéraire') }}</label>
                                <textarea name="notes" id="notes" rows="4" placeholder="{{ __('Décrire l'itinéraire prévu, les conditions spécifiques du test, etc.') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('notes') }}</textarea>
                                 @error('notes') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                        </div>

                        {{-- Buttons: Cancel and Save --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('driving-tests.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:active:bg-indigo-600 dark:focus:ring-indigo-700"
                                     {{ $candidates->isEmpty() || $vehicles->isEmpty() || $admins->isEmpty() ? 'disabled' : '' }}>
                                {{ __('Planifier le Test') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
