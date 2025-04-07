{{-- resources/views/admin/evaluation_criteria/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier le Critère :') }} {{ $criterion->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any()) <div class="mb-6 bg-red-100 ..."> ... </div> @endif

                    <form method="POST" action="{{ route('admin.evaluation-criteria.update', $criterion->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            {{-- Nom --}}
                            <div>
                                <label for="name" class="block font-medium text-sm ...">{{ __('Nom du Critère') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" class="block mt-1 w-full rounded-md ..." value="{{ old('name', $criterion->name) }}" required>
                                @error('name') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                            {{-- Catégorie --}}
                            <div>
                                <label for="category" class="block font-medium text-sm ...">{{ __('Catégorie (Optionnel)') }}</label>
                                <input type="text" name="category" id="category" list="category_suggestions" class="block mt-1 w-full rounded-md ..." value="{{ old('category', $criterion->category) }}" placeholder="Ex: Entretien RH, Technique, Test Conduite...">
                                <datalist id="category_suggestions">
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">
                                    @endforeach
                                </datalist>
                                @error('category') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                            {{-- Description --}}
                             <div>
                                <label for="description" class="block font-medium text-sm ...">{{ __('Description (Optionnel)') }}</label>
                                <textarea name="description" id="description" rows="3" class="block mt-1 w-full rounded-md ...">{{ old('description', $criterion->description) }}</textarea>
                                @error('description') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                             {{-- Actif ? --}}
                            <div>
                                <label for="is_active" class="block font-medium text-sm ...">{{ __('Actif?') }}</label>
                                <select name="is_active" id="is_active" class="block mt-1 w-full rounded-md ..." required>
                                    <option value="1" {{ old('is_active', $criterion->is_active) == 1 ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ old('is_active', $criterion->is_active) == 0 ? 'selected' : '' }}>Non</option>
                                </select>
                                @error('is_active') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                        </div> {{-- Fin space-y-6 --}}

                        <div class="flex items-center justify-end mt-8 border-t pt-6">
                            <a href="{{ route('admin.evaluation-criteria.index') }}" class="underline text-sm ... mr-4">{{ __('Annuler') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 ...">
                                {{ __('Mettre à Jour Critère') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>