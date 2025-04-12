{{-- resources/views/driving_tests/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl ...">{{ __('Modifier Test Conduite :') }} {{ $drivingTest->candidate?->user?->name }} du {{ $drivingTest->test_date->format('d/m/Y') }}</h2></x-slot>
    <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900 dark:text-gray-100">
        @if ($errors->any()) <div class="mb-6 bg-red-100 ..."> ... </div> @endif
        <form method="POST" action="{{ route('driving-tests.update', $drivingTest->id) }}">@csrf @method('PUT') <div class="space-y-6">
            {{-- Candidat --}}
            <div><label for="candidate_id" class="block font-medium text-sm ...">{{ __('Candidat') }} <span class="text-red-500">*</span></label><select name="candidate_id" id="candidate_id" class="block mt-1 w-full rounded-md ..." required><option value="">-- Sélectionner --</option>@foreach($candidates as $candidate)<option value="{{ $candidate->id }}" {{ old('candidate_id', $drivingTest->candidate_id) == $candidate->id ? 'selected' : '' }}>{{ $candidate->first_name }} {{ $candidate->last_name }}</option>@endforeach</select>@error('candidate_id') <p ...>{{ $message }}</p> @enderror</div>
            {{-- Évaluateur --}}
            <div><label for="evaluator_id" class="block font-medium text-sm ...">{{ __('Évaluateur') }} <span class="text-red-500">*</span></label><select name="evaluator_id" id="evaluator_id" class="block mt-1 w-full rounded-md ..." required><option value="">-- Sélectionner --</option>@foreach($evaluators as $evaluator)<option value="{{ $evaluator->id }}" {{ old('evaluator_id', $drivingTest->evaluator_id) == $evaluator->id ? 'selected' : '' }}>{{ $evaluator->name }}</option>@endforeach</select>@error('evaluator_id') <p ...>{{ $message }}</p> @enderror</div>
             {{-- Véhicule --}}
            <div><label for="vehicle_id" class="block font-medium text-sm ...">{{ __('Véhicule Utilisé') }}</label><select name="vehicle_id" id="vehicle_id" class="block mt-1 w-full rounded-md ..."><option value="">-- Sélectionner --</option>@foreach($vehicles as $vehicle)<option value="{{ $vehicle->id }}" {{ old('vehicle_id', $drivingTest->vehicle_id) == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})</option>@endforeach</select>@error('vehicle_id') <p ...>{{ $message }}</p> @enderror</div>
            {{-- Date et Heure --}}
            <div><label for="test_date" class="block font-medium text-sm ...">{{ __('Date et Heure Test') }} <span class="text-red-500">*</span></label><input type="datetime-local" name="test_date" id="test_date" class="block mt-1 w-full rounded-md ..." value="{{ old('test_date', $drivingTest->test_date->format('Y-m-d\TH:i')) }}" required>@error('test_date') <p ...>{{ $message }}</p> @enderror</div>
            {{-- Itinéraire / Détails --}}
            <div><label for="route_details" class="block font-medium text-sm ...">{{ __('Itinéraire / Conditions') }}</label><textarea name="route_details" id="route_details" rows="3" class="block mt-1 w-full rounded-md ...">{{ old('route_details', $drivingTest->route_details) }}</textarea>@error('route_details') <p ...>{{ $message }}</p> @enderror</div>

            <hr class="dark:border-gray-700 my-2">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Résultats du Test</h3>
            {{-- Statut --}}
            <div><label for="status" class="block font-medium text-sm ...">{{ __('Statut du Test') }}</label><select name="status" id="status" class="block mt-1 w-full rounded-md ..." required>@foreach($statuses as $status)<option value="{{ $status }}" {{ old('status', $drivingTest->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>@endforeach</select>@error('status') <p ...>{{ $message }}</p> @enderror</div>
             {{-- Résultat (Passed) --}}
            <div><label for="passed" class="block font-medium text-sm ...">{{ __('Résultat (si Terminé)') }}</label><select name="passed" id="passed" class="block mt-1 w-full rounded-md ..."><option value="">-- Laisser vide si non terminé --</option><option value="1" {{ old('passed', $drivingTest->passed) === true ? 'selected' : '' }}>Réussi</option><option value="0" {{ old('passed', $drivingTest->passed) === false ? 'selected' : '' }}>Échoué</option></select><p class="mt-1 text-xs ...">Choisir seulement si le statut est "Terminé".</p>@error('passed') <p ...>{{ $message }}</p> @enderror</div>
            {{-- Résumé des Résultats --}}
            <div><label for="results_summary" class="block font-medium text-sm ...">{{ __('Résumé Résultats / Commentaires') }}</label><textarea name="results_summary" id="results_summary" rows="4" class="block mt-1 w-full rounded-md ...">{{ old('results_summary', $drivingTest->results_summary) }}</textarea>@error('results_summary') <p ...>{{ $message }}</p> @enderror</div>

        </div>
        {{-- Boutons --}}
        <div class="flex items-center justify-end mt-8 border-t pt-6"><a href="{{ route('driving-tests.show', $drivingTest->id) }}" class="underline ... mr-4">{{ __('Annuler') }}</a><button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 ...">{{ __('Mettre à Jour Test') }}</button></div>
        </form></div></div></div></div>
</x-app-layout>