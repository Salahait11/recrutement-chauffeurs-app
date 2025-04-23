{{-- resources/views/calendar/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- En-tête avec titre -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between"> {{-- Garde la structure même si pas de boutons à droite --}}
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône Calendar Days (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0h18M-4.5 12h22.5" />
                            </svg>
                            Calendrier
                        </div>
                    </h2>
                </div>
                 {{-- Pas de boutons d'action ici --}}
                 {{-- <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3"> ... </div> --}}
            </div>
        </div>
    </div>

     <!-- Filtre Employé -->
     {{-- !! Adapter la condition si ce filtre n'est visible que pour certains rôles !! --}}
     {{-- @if(Auth::user()->isAdmin()) --}}
     <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
             {{-- Utilisation de la grille même pour un seul filtre pour la cohérence --}}
             <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="employee_id_filter" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Voir le calendrier pour :</label>
                    <select name="employee_id" id="employee_id_filter" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        <option value="">-- Tous les Employés --</option>
                        {{-- La variable $employees doit être passée par le contrôleur calendar() --}}
                        @foreach($employees ?? [] as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->user->name ?? 'ID: '.$emp->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                 {{-- Espaces réservés pour alignement --}}
                 <div></div>
                 <div></div>
                 <div></div>
            </div>
             {{-- Pas de bouton "Filtrer", le JS gère le changement --}}
        </div>
     </div>
     {{-- @endif --}}

    <!-- Bloc Calendrier -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
             {{-- Le CSS pour FullCalendar devrait idéalement être dans app.css --}}
             <div id='calendar' class="fc-container"></div> {{-- Calendrier s'affiche ici --}}
        </div>
    </div>

</div>
@endsection

{{-- Script pour recharger la page quand on change l'employé --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Utilise le nouvel ID du select
        const employeeFilter = document.getElementById('employee_id_filter');

        if (employeeFilter) {
            employeeFilter.addEventListener('change', function() {
                const selectedEmployeeId = this.value;
                const currentUrl = new URL(window.location.href);
                // Gère le paramètre employee_id dans l'URL
                if (selectedEmployeeId) {
                    currentUrl.searchParams.set('employee_id', selectedEmployeeId);
                } else {
                    currentUrl.searchParams.delete('employee_id');
                }
                // Recharge la page avec la nouvelle URL (incluant ou non le paramètre)
                window.location.href = currentUrl.toString();
            });
        }
    });
</script>
@endpush