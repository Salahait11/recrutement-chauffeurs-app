{{-- resources/views/admin/vehicles/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier Véhicule :') }} {{ $vehicle->plate_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any()) <div class="mb-6 bg-red-100 ..."> ... </div> @endif

                    <form method="POST" action="{{ route('admin.vehicles.update', $vehicle->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            {{-- Immatriculation --}}
                            <div>
                                <label for="plate_number" class="block font-medium text-sm ...">{{ __('N° Immatriculation') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="plate_number" id="plate_number" class="block mt-1 w-full rounded-md font-mono ..." value="{{ old('plate_number', $vehicle->plate_number) }}" required>
                                @error('plate_number') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Marque --}}
                                <div>
                                    <label for="brand" class="block font-medium text-sm ...">{{ __('Marque') }}</label>
                                    <input type="text" name="brand" id="brand" class="block mt-1 w-full rounded-md ..." value="{{ old('brand', $vehicle->brand) }}">
                                    @error('brand') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                                 {{-- Modèle --}}
                                <div>
                                    <label for="model" class="block font-medium text-sm ...">{{ __('Modèle') }}</label>
                                    <input type="text" name="model" id="model" class="block mt-1 w-full rounded-md ..." value="{{ old('model', $vehicle->model) }}">
                                    @error('model') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                             </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Type --}}
                                <div>
                                    <label for="type" class="block font-medium text-sm ...">{{ __('Type') }}</label>
                                    <input type="text" name="type" id="type" class="block mt-1 w-full rounded-md ..." value="{{ old('type', $vehicle->type) }}">
                                    @error('type') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                                 {{-- Année --}}
                                <div>
                                    <label for="year" class="block font-medium text-sm ...">{{ __('Année Mise en Circulation') }}</label>
                                    <input type="number" name="year" id="year" min="1900" max="{{ date('Y') + 1 }}" step="1" class="block mt-1 w-full rounded-md ..." value="{{ old('year', $vehicle->year) }}">
                                    @error('year') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                            </div>

                             {{-- Disponible ? --}}
                            <div>
                                <label for="is_available" class="block font-medium text-sm ...">{{ __('Disponible?') }}</label>
                                <select name="is_available" id="is_available" class="block mt-1 w-full rounded-md ..." required>
                                    <option value="1" {{ old('is_available', $vehicle->is_available) == 1 ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ old('is_available', $vehicle->is_available) == 0 ? 'selected' : '' }}>Non</option>
                                </select>
                                @error('is_available') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label for="notes" class="block font-medium text-sm ...">{{ __('Notes') }}</label>
                                <textarea name="notes" id="notes" rows="3" class="block mt-1 w-full rounded-md ...">{{ old('notes', $vehicle->notes) }}</textarea>
                                @error('notes') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                        </div> {{-- Fin space-y-6 --}}

                        <div class="flex items-center justify-end mt-8 border-t pt-6">
                            <a href="{{ route('admin.vehicles.index') }}" class="underline text-sm ... mr-4">{{ __('Annuler') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 ...">
                                {{ __('Mettre à Jour Véhicule') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>