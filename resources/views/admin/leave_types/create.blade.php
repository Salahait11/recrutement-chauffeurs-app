{{-- resources/views/leave_types/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer un Nouveau Type de Congé') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Affichage des erreurs --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oups!</strong>
                            <span class="block sm:inline">Veuillez corriger les erreurs ci-dessous.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.leave-types.store') }}">
                        @csrf
                        <div class="space-y-6">
                            {{-- Nom --}}
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nom du Type') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('name', $leaveType->name ?? '') }}" required autofocus>
                                @error('name') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Description --}}
                             <div>
                                <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                                <textarea name="description" id="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('description', $leaveType->description ?? '') }}</textarea>
                                @error('description') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                             {{-- Options Booléennes --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <label for="requires_approval" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Approbation Requise?') }}</label>
                                    <select name="requires_approval" id="requires_approval" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                        {{-- Utilisation de $leaveType pour la valeur par défaut --}}
                                        <option value="1" {{ old('requires_approval', $leaveType->requires_approval ?? true) == true ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ old('requires_approval', $leaveType->requires_approval ?? true) == false ? 'selected' : '' }}>Non</option>
                                    </select>
                                    @error('requires_approval') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                                 <div>
                                    <label for="affects_balance" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Affecte le Solde?') }}</label>
                                    <select name="affects_balance" id="affects_balance" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                        <option value="1" {{ old('affects_balance', $leaveType->affects_balance ?? true) == true ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ old('affects_balance', $leaveType->affects_balance ?? true) == false ? 'selected' : '' }}>Non</option>
                                    </select>
                                     @error('affects_balance') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                                 <div>
                                    <label for="is_active" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Actif?') }}</label>
                                    <select name="is_active" id="is_active" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                        <option value="1" {{ old('is_active', $leaveType->is_active ?? true) == true ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ old('is_active', $leaveType->is_active ?? true) == false ? 'selected' : '' }}>Non</option>
                                    </select>
                                     @error('is_active') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                             {{-- Code Couleur --}}
                             <div>
                                <label for="color_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Code Couleur (Calendrier)') }}</label>
                                <div class="flex items-center mt-1">
                                    <input type="color" name="color_code" id="color_code" class="p-0 h-10 w-14 block bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none" value="{{ old('color_code', $leaveType->color_code ?? '#3498DB') }}">
                                    <input type="text" id="color_code_text" class="ml-2 w-24 rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 text-sm" value="{{ old('color_code', $leaveType->color_code ?? '#3498DB') }}" maxlength="7" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$">
                                </div>
                                 <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format hexadécimal (ex: #3498DB)</p>
                                 @error('color_code') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                {{-- Script pour lier les champs couleur --}}
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const colorPicker = document.getElementById('color_code');
                                        const colorText = document.getElementById('color_code_text');
                                        if(colorPicker && colorText) {
                                            colorPicker.addEventListener('input', (event) => { colorText.value = event.target.value; });
                                            colorText.addEventListener('input', (event) => { if(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i.test(event.target.value)) {colorPicker.value = event.target.value;} });
                                        }
                                    });
                                </script>
                            </div>

                        </div> {{-- Fin space-y-6 --}}

                        <div class="flex items-center justify-end mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <a href="{{ route('admin.leave-types.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">{{ __('Annuler') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Créer Type de Congé') }}
                            </button>
                        </div>
                    </form>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>