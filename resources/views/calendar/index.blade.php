{{-- resources/views/calendar/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4"> {{-- Flex-wrap pour petits écrans --}}
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Calendrier des Absences') }}
            </h2>
            {{-- Filtre Employé --}}
            <div class="flex items-center gap-2">
                 <label for="employee_filter_calendar" class="text-sm font-medium text-gray-700 dark:text-gray-300">Voir pour :</label>
                 <select name="employee_id" id="employee_filter_calendar" class="block w-48 rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Tous les Employés --</option>
                     {{-- La variable $employees doit être passée par le contrôleur calendar() --}}
                    @foreach($employees ?? [] as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->user->name ?? 'ID: '.$emp->id }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                     <div id='calendar'></div> {{-- Calendrier s'affiche ici --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Script pour recharger le calendrier quand on change l'employé --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeFilter = document.getElementById('employee_filter_calendar');
            // Récupérer l'instance du calendrier (suppose qu'elle est globale ou accessible)
            // Il faut adapter le JS dans app.js pour stocker l'instance du calendrier
            // Pour l'instant, on recharge la page avec le paramètre, c'est plus simple
            if (employeeFilter) {
                employeeFilter.addEventListener('change', function() {
                    const selectedEmployeeId = this.value;
                    const currentUrl = new URL(window.location.href);
                    if (selectedEmployeeId) {
                        currentUrl.searchParams.set('employee_id', selectedEmployeeId);
                    } else {
                        currentUrl.searchParams.delete('employee_id');
                    }
                    window.location.href = currentUrl.toString(); // Recharge la page avec le nouveau paramètre
                });
            }
        });
    </script>
    @endpush

</x-app-layout>