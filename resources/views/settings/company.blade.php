<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Paramètres de l\'entreprise') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.company.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            {{-- Logo --}}
                            <div>
                                <label for="logo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Logo de l\'entreprise') }}</label>
                                @if($settings && $settings->logo_path)
                                    <div class="mt-2 mb-4">
                                        <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo actuel" class="h-20 w-auto">
                                    </div>
                                @endif
                                <input type="file" name="logo" id="logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100
                                    dark:file:bg-indigo-900 dark:file:text-indigo-300
                                    dark:hover:file:bg-indigo-800">
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nom de l'entreprise --}}
                            <div>
                                <label for="company_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nom de l\'entreprise') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="company_name" id="company_name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('company_name', $settings->company_name ?? '') }}" required>
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Adresse --}}
                            <div>
                                <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Adresse') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('address', $settings->address ?? '') }}" required>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Téléphone --}}
                                <div>
                                    <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Téléphone') }} <span class="text-red-500">*</span></label>
                                    <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('phone', $settings->phone ?? '') }}" required>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }} <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('email', $settings->email ?? '') }}" required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Site web --}}
                            <div>
                                <label for="website" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Site web') }}</label>
                                <input type="url" name="website" id="website" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('website', $settings->website ?? '') }}">
                                @error('website')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                                <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('description', $settings->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Couleur primaire --}}
                                <div>
                                    <label for="primary_color" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Couleur primaire') }} <span class="text-red-500">*</span></label>
                                    <div class="mt-1 flex">
                                        <input type="color" name="primary_color" id="primary_color" class="h-10 w-20 rounded-md border-gray-300 dark:border-gray-700" value="{{ old('primary_color', $settings->primary_color ?? '#4F46E5') }}" required>
                                        <input type="text" value="{{ old('primary_color', $settings->primary_color ?? '#4F46E5') }}" class="ml-2 flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" readonly>
                                    </div>
                                    @error('primary_color')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Couleur secondaire --}}
                                <div>
                                    <label for="secondary_color" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Couleur secondaire') }} <span class="text-red-500">*</span></label>
                                    <div class="mt-1 flex">
                                        <input type="color" name="secondary_color" id="secondary_color" class="h-10 w-20 rounded-md border-gray-300 dark:border-gray-700" value="{{ old('secondary_color', $settings->secondary_color ?? '#1F2937') }}" required>
                                        <input type="text" value="{{ old('secondary_color', $settings->secondary_color ?? '#1F2937') }}" class="ml-2 flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" readonly>
                                    </div>
                                    @error('secondary_color')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <x-primary-button>
                                    {{ __('Enregistrer les modifications') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mise à jour des champs texte pour les couleurs
            ['primary_color', 'secondary_color'].forEach(function(id) {
                const colorInput = document.getElementById(id);
                const textInput = colorInput.nextElementSibling;
                
                colorInput.addEventListener('input', function(e) {
                    textInput.value = e.target.value.toUpperCase();
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 