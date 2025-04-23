{{-- resources/views/leave_types/create.blade.php --}}
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
     @if ($errors->any())
        <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800">
            <div class="flex">
                <div class="flex-shrink-0"> <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /> </svg> </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Oups! Erreur(s) de validation</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                        <ul role="list" class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- En-tête avec titre -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône Calendar Plus (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0h18M-4.5 12h22.5m-10.5-4.5v9" />
                            </svg>
                            Créer un Nouveau Type de Congé
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('admin.leave-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
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
        <form method="POST" action="{{ route('admin.leave-types.store') }}" class="p-6 space-y-6">
            @csrf
            <div class="space-y-4"> {{-- Inner container --}}

                {{-- Nom --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du Type <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                 <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                 {{-- Options Booléennes --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="requires_approval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Approbation Requise?</label>
                        <select name="requires_approval" id="requires_approval" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                             {{-- Default to Yes (1) --}}
                            <option value="1" @selected(old('requires_approval', '1') == '1')>Oui</option>
                            <option value="0" @selected(old('requires_approval', '1') == '0')>Non</option>
                        </select>
                         @error('requires_approval') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                     <div>
                        <label for="affects_balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Affecte le Solde?</label>
                        <select name="affects_balance" id="affects_balance" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                             {{-- Default to Yes (1) --}}
                            <option value="1" @selected(old('affects_balance', '1') == '1')>Oui</option>
                            <option value="0" @selected(old('affects_balance', '1') == '0')>Non</option>
                        </select>
                         @error('affects_balance') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                     <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Actif?</label>
                        <select name="is_active" id="is_active" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                             {{-- Default to Yes (1) --}}
                            <option value="1" @selected(old('is_active', '1') == '1')>Oui</option>
                            <option value="0" @selected(old('is_active', '1') == '0')>Non</option>
                        </select>
                         @error('is_active') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                 {{-- Code Couleur --}}
                 <div>
                    <label for="color_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code Couleur (Calendrier)</label>
                    <div class="flex items-center mt-1">
                        <input type="color" name="color_code" id="color_code" value="{{ old('color_code', '#3498DB') }}"
                               class="p-0 h-10 w-14 block bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none">
                        <input type="text" id="color_code_text" value="{{ old('color_code', '#3498DB') }}" maxlength="7" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                               class="ml-2 w-24 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    </div>
                     <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format hexadécimal (ex: #3498DB)</p>
                     @error('color_code') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                     {{-- Le script JS pour lier les champs couleur --}}
                     <script> document.addEventListener('DOMContentLoaded', function() { const cp=document.getElementById('color_code'), ct=document.getElementById('color_code_text'); if(cp && ct) { ct.value = cp.value; cp.addEventListener('input', (e) => ct.value=e.target.value); ct.addEventListener('input', (e) => { if(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i.test(e.target.value)) cp.value=e.target.value; }); } }); </script>
                </div>

            </div> {{-- Fin space-y-4 --}}

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                 <a href="{{ route('admin.leave-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    Créer Type de Congé
                </button>
            </div>
        </form>
    </div>
</div>
@endsection