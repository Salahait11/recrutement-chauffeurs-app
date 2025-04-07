{{-- resources/views/admin/reports/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rapports & Statistiques') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Carte Statistiques Candidats --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4 dark:border-gray-700">Statut des Candidats</h3>
                        @if($candidateStats->count() > 0)
                            <ul class="space-y-1 text-sm">
                                @foreach($candidateStats as $status => $count)
                                <li class="flex justify-between">
                                    <span>{{ ucfirst($status) }}</span>
                                    <span class="font-semibold">{{ $count }}</span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">Aucune donnée candidat.</p>
                        @endif
                        {{-- Lien vers la page candidats --}}
                        <div class="mt-4"><a href="{{ route('candidates.index') }}" class="text-sm text-blue-500 hover:underline">Voir tous les candidats →</a></div>
                    </div>
                </div>

                {{-- Carte Statistiques Employés --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                         <h3 class="text-lg font-semibold border-b pb-2 mb-4 dark:border-gray-700">Statut des Employés</h3>
                         @if($employeeStats->count() > 0)
                            <ul class="space-y-1 text-sm">
                                 @foreach($employeeStats as $status => $count)
                                <li class="flex justify-between">
                                    <span>{{ ucfirst($status) }}</span>
                                    <span class="font-semibold">{{ $count }}</span>
                                </li>
                                @endforeach
                            </ul>
                         @else
                             <p class="text-gray-500 italic">Aucune donnée employé.</p>
                         @endif
                         {{-- Lien vers la page employés --}}
                        <div class="mt-4"><a href="{{ route('employees.index') }}" class="text-sm text-blue-500 hover:underline">Voir tous les employés →</a></div>
                    </div>
                </div>

                 {{-- Carte Prochaines Absences/Congés --}}
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:col-span-2"> {{-- Prend toute la largeur sur mobile/tablette --}}
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                         <h3 class="text-lg font-semibold border-b pb-2 mb-4 dark:border-gray-700">Prochains Congés & Absences (30 jours)</h3>
                         @if($upcomingEvents->count() > 0)
                            <ul class="space-y-2 text-sm">
                                @foreach($upcomingEvents as $event)
                                <li class="border-l-4 pl-3 @if(str_starts_with($event->type, 'Absence')) border-orange-400 @else border-green-400 @endif">
                                    <span class="font-semibold">{{ $event->employee_name }}</span> -
                                    <span class="{{ $event->css_class }}">{{ $event->type }}</span><br>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Du {{ $event->date->format('d/m/Y H:i') }} au {{ $event->end_date->format('d/m/Y H:i') }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">Aucun événement prévu dans les 30 prochains jours.</p>
                        @endif
                        {{-- Lien vers le calendrier --}}
                        <div class="mt-4"><a href="{{ route('calendar.index') }}" class="text-sm text-blue-500 hover:underline">Voir le calendrier complet →</a></div>
                    </div>
                </div>

                {{-- Ajouter d'autres cartes de rapport ici --}}

            </div>
        </div>
    </div>
</x-app-layout>