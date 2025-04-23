{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- En-tête avec titre -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône Chart Bar (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 3 21v-7.875ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            Rapports & Statistiques
                        </div>
                    </h2>
                </div>
                {{-- Pas de bouton d'action principal ici --}}
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if (session('success'))
    <div class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p> </div> </div> </div>
    @endif
    @if (session('error'))
    <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p> </div> </div> </div>
    @endif

     <!-- Filtre Période -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-4">
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Événements Du</label>
                        <input type="date" name="start_date" id="start_date"
                               value="{{ $startDate->toDateString() }}" {{-- Utilise $startDate du contrôleur --}}
                               class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                         @error('start_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Au</label>
                        <input type="date" name="end_date" id="end_date"
                               value="{{ $endDate->toDateString() }}" {{-- Utilise $endDate du contrôleur --}}
                               class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                        @error('end_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div></div> {{-- Placeholder --}}
                     {{-- Bouton Filtrer déplacé à droite --}}
                     <div class="flex items-end justify-end">
                         <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                             <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /> </svg>
                             Filtrer Période
                         </button>
                     </div>
                 </div>
                  {{-- Pas de bouton Reset ici pour être cohérent --}}
            </form>
        </div>
    </div>

    <!-- Section Statistiques Générales -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
         <div class="p-6 space-y-6">
             <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300 border-b border-gray-200 dark:border-gray-700 pb-3">Statistiques Globales (Actuelles)</h3>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 {{-- Carte Stats Candidats --}}
                 <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                     <h4 class="text-md font-semibold mb-3 text-gray-700 dark:text-gray-300">Statut Candidats</h4>
                     <div class="mb-4 h-64 flex justify-center items-center">
                          @if($candidateChart)
                             <x-chartjs-component :chart="$candidateChart" />
                          @else
                             <p class="text-gray-500 dark:text-gray-400 italic">Aucune donnée candidat.</p>
                          @endif
                     </div>
                      @if($rawCandidateStats && $rawCandidateStats->count() > 0)
                        <ul class="space-y-1 text-sm mt-4 border-t border-gray-300 dark:border-gray-600 pt-3">
                            @foreach($rawCandidateStats as $status => $count)
                            <li class="flex justify-between text-gray-600 dark:text-gray-400"><span>{{ $statusLabels[$status] ?? ucfirst($status) }}</span><span class="font-medium text-gray-800 dark:text-gray-200">{{ $count }}</span></li>
                            @endforeach
                            <li class="flex justify-between font-bold border-t border-gray-400 dark:border-gray-500 pt-1 mt-1 text-gray-800 dark:text-gray-200"><span>Total</span><span>{{ $rawCandidateStats->sum() }}</span></li>
                        </ul>
                      @endif
                     <div class="mt-4 text-center"><a href="{{ route('candidates.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Voir détails candidats →</a></div>
                 </div>

                 {{-- Carte Stats Employés --}}
                 <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                      <h4 class="text-md font-semibold mb-3 text-gray-700 dark:text-gray-300">Statut Employés</h4>
                       <div class="mb-4 h-64 flex justify-center items-center">
                            @if($employeeChart)
                                <x-chartjs-component :chart="$employeeChart" />
                             @else
                                <p class="text-gray-500 dark:text-gray-400 italic">Aucune donnée employé.</p>
                             @endif
                       </div>
                       @if($rawEmployeeStats && $rawEmployeeStats->count() > 0)
                         <ul class="space-y-1 text-sm mt-4 border-t border-gray-300 dark:border-gray-600 pt-3">
                              @foreach($rawEmployeeStats as $status => $count)
                             <li class="flex justify-between text-gray-600 dark:text-gray-400"><span>{{ ucfirst($status) }}</span><span class="font-medium text-gray-800 dark:text-gray-200">{{ $count }}</span></li>
                             @endforeach
                              <li class="flex justify-between font-bold border-t border-gray-400 dark:border-gray-500 pt-1 mt-1 text-gray-800 dark:text-gray-200"><span>Total</span><span>{{ $rawEmployeeStats->sum() }}</span></li>
                         </ul>
                       @else
                         <p class="text-center text-gray-500 dark:text-gray-400 italic mt-4 border-t pt-4 dark:border-gray-700">Aucune donnée employé.</p>
                       @endif
                      <div class="mt-4 text-center"><a href="{{ route('employees.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Voir détails employés →</a></div>
                 </div>
             </div>
         </div>
    </div>

    <!-- Section Événements sur la Période -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6 space-y-4">
            <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">
              Congés & Absences du {{ $startDate->isoFormat('D MMM YYYY') }} au {{ $endDate->isoFormat('D MMM YYYY') }}
            </h3>
             @if($periodEvents && $periodEvents->count() > 0)
                 <ul class="space-y-3">
                     @foreach($periodEvents as $event)
                     <li class="border-l-4 pl-3 py-1
                         @if($event['is_absence'])
                             {{ $event['css_class'] ? 'border-orange-400 dark:border-orange-500' : 'border-gray-400 dark:border-gray-500' }}
                         @else
                             {{ $event['css_class'] ? 'border-green-400 dark:border-green-500' : 'border-blue-400 dark:border-blue-500' }}
                         @endif">
                            @if($event['url'])
                                <a href="{{ $event['url'] }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $event['employee_name'] }}</span> - <span class="text-sm {{ $event['css_class'] ?? 'text-gray-700 dark:text-gray-300' }}">{{ $event['type'] }}</span>
                                </a>
                            @else
                                 <span class="font-medium text-gray-900 dark:text-gray-100">{{ $event['employee_name'] }}</span> - <span class="text-sm {{ $event['css_class'] ?? 'text-gray-700 dark:text-gray-300' }}">{{ $event['type'] }}</span>
                            @endif
                            <br>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{-- Date formatting logic remains the same --}}
                                @if($event['date']->isSameDay($event['end_date'])) Le {{ $event['date']->isoFormat('ddd D MMM YYYY') }} @if($event['date']->format('H:i:s') !== '00:00:00' || $event['end_date']->format('H:i:s') !== '23:59:59') (de {{ $event['date']->format('H:i') }} à {{ $event['end_date']->format('H:i') }}) @endif
                                @else Du {{ $event['date']->isoFormat('ddd D MMM YYYY') }} au {{ $event['end_date']->isoFormat('ddd D MMM YYYY') }} @endif
                            </span>
                     </li>
                     @endforeach
                 </ul>
             @else
                 <p class="text-gray-500 dark:text-gray-400 italic">Aucun congé ou absence enregistré pour cette période.</p>
             @endif
             <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4 text-right">
                 <a href="{{ route('calendar.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Voir le calendrier complet →</a>
             </div>
        </div>
    </div>

    <!-- Section Graphique Congés par Type -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6 space-y-4">
             <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">
                  Congés Approuvés par Type ({{ $startDate->isoFormat('D MMM') }} - {{ $endDate->isoFormat('D MMM YYYY') }})
              </h3>
              <div class="h-72 flex justify-center items-center"> {{-- Fixed height, adjust as needed --}}
                   @if($leaveByTypeChart)
                      <x-chartjs-component :chart="$leaveByTypeChart" />
                   @else
                      <p class="text-gray-500 dark:text-gray-400 italic">Aucune donnée de congé approuvé pour cette période.</p>
                   @endif
              </div>
        </div>
    </div>

    <!-- Section Exports -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6 space-y-4">
             <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Exports</h3>
             <div id="export-buttons" class="flex flex-wrap gap-4">
                   {{-- Bouton Export Employés CSV (Styled as secondary button) --}}
                  <a href="{{ route('admin.reports.export.employees') }}"
                     class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                      <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                      Employés Actifs (CSV)
                  </a>
                  {{-- Ajouter d'autres boutons ici avec le même style --}}
             </div>
        </div>
    </div>

</div> {{-- Fin space-y-6 --}}
@endsection