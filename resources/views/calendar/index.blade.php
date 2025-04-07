{{-- resources/views/calendar/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Calendrier des Absences') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                     {{-- Le conteneur où FullCalendar va s'afficher --}}
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Optionnel: Charger les CSS de FullCalendar si pas déjà inclus globalement --}}
    {{-- @push('styles')
         <link href='...' rel='stylesheet' /> // Chemin vers le CSS de FullCalendar si nécessaire
    @endpush --}}
    {{-- Normalement, Vite/app.css devrait gérer ça si bien configuré, mais on vérifie --}}

</x-app-layout>