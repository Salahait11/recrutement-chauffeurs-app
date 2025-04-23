{{-- resources/views/leave_requests/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Messages Flash (seront affichés sur la page de redirection) -->
    @if(session('error'))
        <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800">
             <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
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
                            Nouvelle Demande de Congé
                            {{-- Adapter si c'est une création par admin ou par l'employé lui-même --}}
                            {{-- @if(Auth::user()->isAdmin()) (par Admin) @endif --}}
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
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
        <form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            {{-- Section Sélection Employé (Admin seulement) --}}
            {{-- !! Adapter la condition à votre logique de rôle/permission !! --}}
            @if(Auth::user()->isAdmin())
                 <div class="space-y-4 pb-6 border-b border-gray-200 dark:border-gray-700">
                     <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Employé Concerné</h3>
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 sr-only">Employé Concerné</label> {{-- sr-only car titre h3 fait office de label --}}
                        <select name="employee_id" id="employee_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">-- Sélectionner un employé --</option>
                            @foreach($employees ?? [] as $emp) {{-- Assumes $employees passed --}}
                                <option value="{{ $emp->id }}" @selected(old('employee_id') == $emp->id)>
                                    {{ $emp->user->name ?? 'Employé ID: '.$emp->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        @if(!isset($employees) || $employees->isEmpty())
                             <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">Aucun employé actif trouvé pour la sélection.</p>
                         @endif
                    </div>
                </div>
            @else
                {{-- Si ce n'est pas un admin, on soumet pour l'utilisateur connecté --}}
                <input type="hidden" name="employee_id" value="{{ Auth::user()->employee->id ?? '' }}"> {{-- Assumes user has one employee profile --}}
            @endif


            <!-- Détails de la Demande -->
            <div class="space-y-4">
                 <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Détails de la Demande</h3>

                 {{-- Type de Congé --}}
                <div>
                    <label for="leave_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type de Congé <span class="text-red-500">*</span></label>
                    <select name="leave_type_id" id="leave_type_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        <option value="">-- Sélectionner un type --</option>
                        @foreach($leaveTypes as $type) {{-- Assumes $leaveTypes passed --}}
                            <option value="{{ $type->id }}" @selected(old('leave_type_id') == $type->id)>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('leave_type_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Dates Début et Fin --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date et Heure de Début <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('start_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date et Heure de Fin <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('end_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                 {{-- Motif / Raison --}}
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motif / Commentaires</label>
                    <textarea name="reason" id="reason" rows="4" placeholder="Raison de la demande (optionnel)..."
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">{{ old('reason') }}</textarea>
                    @error('reason') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Justificatif (Optionnel) --}}
                 <div>
                    <label for="attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Justificatif (si nécessaire)</label>
                    {{-- Style pour input file un peu plus cohérent --}}
                    <input type="file" name="attachment" id="attachment"
                           class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-l-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/40 dark:file:text-blue-300
                                  hover:file:bg-blue-100 dark:hover:file:bg-blue-900/60">
                    @error('attachment') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    Soumettre la Demande
                </button>
            </div>
        </form>
    </div>
</div>
@endsection