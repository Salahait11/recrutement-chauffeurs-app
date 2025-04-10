{{-- resources/views/admin/reports/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Rapports & Statistiques') }}
            </h2>

            {{-- Formulaire Filtre Dates pour les Événements --}}
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-3 text-sm">
                <div>
                    <label for="start_date" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Événements Du</label>
                    <input type="date" name="start_date" id="start_date"
                           class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                           value="{{ $startDate->toDateString() }}">
                </div>
                <div>
                    <label for="end_date" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Au</label>
                    <input type="date" name="end_date" id="end_date"
                           class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                           value="{{ $endDate->toDateString() }}">
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Filtrer Période
                </button>
                 {{-- Bouton Réinitialiser Dates --}}
                 @if(request('start_date') || request('end_date'))
                     <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" title="Afficher période par défaut">
                         Période Défaut
                     </a>
                 @endif
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

             {{-- Messages Flash --}}
            @if (session('success')) <div class="mb-6 bg-green-100 ...">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="mb-6 bg-red-100 ...">{{ session('error') }}</div> @endif

             {{-- Section Statistiques Générales --}}
             <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Statistiques Globales (Actuelles)</h3>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                 {{-- Carte Stats Candidats --}}
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                     <div class="p-6 text-gray-900 dark:text-gray-100">
                         <h4 class="text-lg font-semibold border-b pb-2 mb-4 dark:border-gray-700">Statut Candidats</h4>
                         <div class="mb-4 h-64 flex justify-center items-center">
                              {{-- Utilisation de l'objet $candidateChart --}}
                              @if($candidateChart)
                                 <x-chartjs-component :chart="$candidateChart" />
                              @else
                                 <p class="text-gray-500 dark:text-gray-400 italic">Aucune donnée candidat.</p>
                              @endif
                         </div>
                          {{-- Vérifie $rawCandidateStats avant la boucle --}}
                          @if($rawCandidateStats && $rawCandidateStats->count() > 0)
                            <ul class="space-y-1 text-sm mt-4 border-t pt-4 dark:border-gray-700">
                                @foreach($rawCandidateStats as $status => $count) <li class="flex justify-between"><span>{{ ucfirst($status) }}</span><span class="font-semibold">{{ $count }}</span></li> @endforeach
                                <li class="flex justify-between font-bold border-t dark:border-gray-600 pt-1 mt-1"><span>Total</span><span>{{ $rawCandidateStats->sum() }}</span></li>
                            </ul>
                          @endif
                         <div class="mt-4 text-center"><a href="{{ route('candidates.index') }}" class="text-sm text-blue-500 hover:underline">Voir tous les candidats →</a></div>
                     </div>
                 </div>
                 {{-- Carte Stats Employés --}}
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                     <div class="p-6 text-gray-900 dark:text-gray-100">
                          <h4 class="text-lg font-semibold border-b pb-2 mb-4 dark:border-gray-700">Statut Employés</h4>
                           <div class="mb-4 h-64 flex justify-center items-center">
                                {{-- Utilisation de l'objet $employeeChart --}}
                                @if($employeeChart) <x-chartjs-component :chart="$employeeChart" />
                                 @else <p class="text-gray-500 dark:text-gray-400 italic">Aucune donnée employé.</p> @endif
                           </div>
                           {{-- Vérifie $rawEmployeeStats avant la boucle --}}
                           @if($rawEmployeeStats && $rawEmployeeStats->count() > 0)
                             <ul class="space-y-1 text-sm mt-4 border-t pt-4 dark:border-gray-700">
                                  @foreach($rawEmployeeStats as $status => $count) <li class="flex justify-between"><span>{{ ucfirst($status) }}</span><span class="font-semibold">{{ $count }}</span></li> @endforeach
                                  <li class="flex justify-between font-bold border-t dark:border-gray-600 pt-1 mt-1"><span>Total</span><span>{{ $rawEmployeeStats->sum() }}</span></li>
                             </ul>
                           @else
                             <p class="text-center text-gray-500 dark:text-gray-400 italic mt-4 border-t pt-4 dark:border-gray-700">Aucune donnée employé.</p>
                           @endif
                          <div class="mt-4 text-center"><a href="{{ route('employees.index') }}" class="text-sm text-blue-500 hover:underline">Voir tous les employés →</a></div>
                     </div>
                 </div>
             </div> {{-- Fin grid stats --}}


              {{-- Section Événements sur la Période Sélectionnée --}}
              <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">
                  Congés & Absences du {{ $startDate->isoFormat('D MMM YYYY') }} au {{ $endDate->isoFormat('D MMM YYYY') }}
              </h3>
              <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900 dark:text-gray-100">
                       {{-- Utilise $periodEvents --}}
                      @if($periodEvents && $periodEvents->count() > 0)
                         <ul class="space-y-3">
                             @foreach($periodEvents as $event)
                             <li class="border-l-4 pl-3 {{ $event->is_absence ? ($event->css_class ? 'border-orange-400 dark:border-orange-500' : 'border-gray-400 dark:border-gray-500') : ($event->css_class ? 'border-green-400 dark:border-green-500' : 'border-blue-400 dark:border-blue-500') }}">
                                   @if($event->url)<a href="{{ $event->url }}" class="text-blue-600 dark:text-blue-400 hover:underline"> <span class="font-semibold">{{ $event->employee_name }}</span> - <span class="{{ $event->css_class ?? '' }}">{{ $event->type }}</span></a>
                                   @else <span class="font-semibold">{{ $event->employee_name }}</span> - <span class="{{ $event->css_class ?? '' }}">{{ $event->type }}</span> @endif
                                   <br>
                                   <span class="text-xs text-gray-600 dark:text-gray-400">
                                       @if($event->date->isSameDay($event->end_date)) Le {{ $event->date->isoFormat('ddd D MMM YYYY') }} @if($event->date->format('H:i:s') !== '00:00:00' || $event->end_date->format('H:i:s') !== '23:59:59') (de {{ $event->date->format('H:i') }} à {{ $event->end_date->format('H:i') }}) @endif
                                       @else Du {{ $event->date->isoFormat('ddd D MMM YYYY') }} au {{ $event->end_date->isoFormat('ddd D MMM YYYY') }} @endif
                                   </span>
                             </li>
                             @endforeach
                         </ul>
                     @else
                         <p class="text-gray-500 dark:text-gray-400 italic">Aucun congé ou absence enregistré pour cette période.</p>
                     @endif
                     <div class="mt-4"><a href="{{ route('calendar.index') }}" class="text-sm text-blue-500 hover:underline">Voir le calendrier complet →</a></div>
                 </div>
             </div>

             {{-- Section Graphique Congés par Type sur la Période --}}
              <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">
                  Congés Approuvés par Type ({{ $startDate->isoFormat('D MMM') }} - {{ $endDate->isoFormat('D MMM YYYY') }})
              </h3>
              <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900 dark:text-gray-100">
                      <div class="h-72">
                           {{-- Utilise l'objet $leaveByTypeChart --}}
                            @if($leaveByTypeChart)
                               <x-chartjs-component :chart="$leaveByTypeChart" />
                            @else
                               <p class="text-gray-500 dark:text-gray-400 italic text-center pt-10">Aucune donnée de congé approuvé pour cette période.</p>
                            @endif
                      </div>
                 </div>
             </div>


              {{-- Section Exports --}}
              <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Exports</h3>
              <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900 dark:text-gray-100">
                     <div id="export-buttons" class="flex flex-wrap gap-4">
                           {{-- Bouton Export Employés CSV --}}
                          <a href="{{ route('admin.reports.export.employees') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 ..."> <svg>...</svg> Employés Actifs (CSV) </a>
                          {{-- Ajouter d'autres boutons ici --}}
                     </div>
                 </div>
             </div>

         </div> {{-- Fin max-w --}}
     </div> {{-- Fin py-12 --}}

      {{-- Pas besoin de @push('scripts') si delivery='cdn' est dans config/chart.js.php --}}

 </x-app-layout>