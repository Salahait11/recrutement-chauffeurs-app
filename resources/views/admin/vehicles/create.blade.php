{{-- resources/views/admin/vehicles/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Messages Flash -->
    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p> </div> </div> </div>
    @endif
    @if(session('error'))
        <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p> </div> </div> </div>
    @endif

    <!-- En-tête avec titre -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône Truck (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path d="M9 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" /> <path d="M19 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16.5V17h2.5a2.5 2.5 0 0 0 0-5H11a3 3 0 0 0-3 3V16h1l1.6-3.2a1 1 0 0 1 .8-.4h2.2a1 1 0 0 1 .8.4L13 16.5Zm4-9h1.5a2.5 2.5 0 0 1 0 5H17m-5.4-5H10a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h2.1a1 1 0 0 0 .8-.4L13 12.5M3 16v-5h1m8 5v-5h1m0-4h4a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1Zm0 0h-2M12 7.5h2.5" />
                            </svg>
                            Ajouter un Nouveau Véhicule
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <form method="POST" action="{{ route('admin.vehicles.store') }}" class="p-6 space-y-6">
            @csrf
            <div class="space-y-4"> {{-- Inner container --}}

                {{-- Immatriculation --}}
                <div>
                    <label for="plate_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">N° Immatriculation <span class="text-red-500">*</span></label>
                    <input type="text" name="plate_number" id="plate_number" required autofocus value="{{ old('plate_number') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm font-mono uppercase">
                    @error('plate_number') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Marque --}}
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marque</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('brand') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                     {{-- Modèle --}}
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Modèle</label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('model') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                 </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                        <input type="text" name="type" id="type" value="{{ old('type') }}" placeholder="Ex: Fourgon, Camionnette, PL..."
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('type') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                     {{-- Année --}}
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Année Mise en Circulation</label>
                        <input type="number" name="year" id="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 1 }}" step="1" placeholder="{{ date('Y') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('year') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                 {{-- Disponible ? --}}
                <div>
                    <label for="is_available" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Disponible? <span class="text-red-500">*</span></label>
                     {{-- Default to Yes (1) on create --}}
                    <select name="is_available" id="is_available" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        <option value="1" @selected(old('is_available', '1') == '1')>Oui</option>
                        <option value="0" @selected(old('is_available', '1') == '0')>Non (ex: en entretien)</option>
                    </select>
                    @error('is_available') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Entretien à prévoir, particularités..."
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">{{ old('notes') }}</textarea>
                    @error('notes') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

            </div> {{-- Fin space-y-4 --}}

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    Ajouter Véhicule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection