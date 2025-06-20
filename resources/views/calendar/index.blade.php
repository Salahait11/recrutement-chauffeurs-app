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
        <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100 relative">
            {{-- Indicateur de chargement --}}
            <div id="calendar-loading" class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Chargement des événements...</p>
                </div>
            </div>
            
            {{-- Le CSS pour FullCalendar devrait idéalement être dans app.css --}}
            <div id='calendar' class="fc-container"></div>
        </div>
    </div>

    {{-- Modal pour afficher les détails de l'absence --}}
    <div id="absence-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" x-data="{ show: false }" x-show="show" @keydown.escape.window="show = false">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6" 
                 @click.away="show = false">
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button type="button" class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none" @click="show = false">
                        <span class="sr-only">Fermer</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="absence-modal-content">
                    {{-- Le contenu sera chargé dynamiquement --}}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- Script pour recharger la page quand on change l'employé --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const employeeFilter = document.getElementById('employee_id_filter');
        const absenceModal = document.getElementById('absence-modal');
        const absenceModalContent = document.getElementById('absence-modal-content');

        if (calendarEl) {
            const calendar = new Calendar(calendarEl, {
                // ... configuration existante ...
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    
                    if (info.event.extendedProps.type === 'absence') {
                        // Afficher la modal pour les absences
                        fetch(`/admin/absences/${info.event.id.replace('absence_', '')}`)
                            .then(response => response.text())
                            .then(html => {
                                absenceModalContent.innerHTML = html;
                                absenceModal.classList.remove('hidden');
                            })
                            .catch(error => {
                                console.error('Erreur lors du chargement des détails:', error);
                                alert('Erreur lors du chargement des détails de l\'absence');
                            });
                    } else if (info.event.url) {
                        // Pour les autres types d'événements (congés), rediriger vers l'URL
                        window.location.href = info.event.url;
                    }
                }
            });

            calendar.render();

            // Gestionnaire pour fermer la modal avec la touche Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !absenceModal.classList.contains('hidden')) {
                    absenceModal.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush