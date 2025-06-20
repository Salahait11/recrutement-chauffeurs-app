{{-- Vue partielle pour les détails d'une absence --}}
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Détails de l'Absence
        </h3>
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $absence->is_justified ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
            {{ $absence->is_justified ? 'Justifiée' : 'Non justifiée' }}
        </span>
    </div>

    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Employé</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $absence->employee->user->name ?? 'N/A' }}
                </dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $absence->absence_date->format('d/m/Y') }}
                </dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Heure de début</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $absence->start_time ? \Carbon\Carbon::parse($absence->start_time)->format('H:i') : '-' }}
                </dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Heure de fin</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $absence->end_time ? \Carbon\Carbon::parse($absence->end_time)->format('H:i') : '-' }}
                </dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Motif</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $absence->reason_type ?? 'Non spécifié' }}
                </dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Enregistré par</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $absence->recorder->name ?? 'Système' }}
                </dd>
            </div>

            @if($absence->notes)
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $absence->notes }}
                </dd>
            </div>
            @endif
        </dl>
    </div>

    <div class="mt-5 sm:mt-6 flex justify-end space-x-3">
        <a href="{{ route('admin.absences.edit', $absence->id) }}" 
           class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Modifier
        </a>
        <button type="button" 
                class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                onclick="document.getElementById('absence-modal').classList.add('hidden')">
            Fermer
        </button>
    </div>
</div> 