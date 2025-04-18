{{-- resources/views/admin/reports/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Rapports & Statistiques') }}
            </h2>

            {{-- Formulaire Filtre Dates pour les Événements --}}
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-3 text-sm">
                {{-- Champ caché dummy pour éviter soumission vide si aucun filtre --}}
                {{-- <input type="hidden" name="filter" value="1"> --}}
                <div>
                    <label for="start_date" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Événements Du</label>
                    <input type="date" name="start_date" id="start_date"
                           class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                           value="{{ $startDate->toDateString() }}"> {{-- Utilise $startDate du contrôleur --}}
                </div>
                <div>
                    <label for="end_date" class="block font-medium text-xs text-gray-700 dark:text-gray-300">Au</label>
                    <input type="date" name="end_date" id="end_date"
                           class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                           value="{{ $endDate->toDateString() }}"> {{-- Utilise $endDate du contrôleur --}}
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Filtrer Période
                </button>
                 {{-- Bouton Réinitialiser Dates --}}
                 {{-- Affiche si une date est différente de la valeur par défaut (1 mois avant / aujourd'hui) --}}
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
             @if (session('success')) <div class="mb-6 bg-green-100 border border-green-400 text-green-700 dark:text-green-200 dark:border-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div> @endif
             @if (session('error')) <div class="mb-6 bg-red-100 border border-red-400 text-red-700 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div> @endif

             {{-- Section Statistiques Générales --}}
             <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Statistiques Globales (Actuelles)</h3>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                 {{-- Carte Stats Candidats --}}
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                     <div class="p-6 text-gray-900 dark:text-gray-100">
                         <h4 class="text-lg font-semibold border-b pb-2 mb-4 dark:border-gray-700">Statut Candidats</h4>
                         <div class="mb-4 h-64 flex justify-center items-center">
                              {{-- Afficher le graphique si l'objet $candidateChart existe --}}
                              @if($candidateChart)
                                 {{-- Le composant Blade du package laravel-chartjs --}}
                                 <x-chartjs-component :chart="$candidateChart" />
                              @else
                                 <p class="text-gray-500 dark:text-gray-400 italic">Aucune donnée candidat.</p>
                              @endif
                         </div>
                          {{-- Affichage optionnel des chiffres bruts (vous pouvez supprimer cette partie si le graphique suffit) --}}
                          @if($rawCandidateStats && $rawCandidateStats->count() > 0)
                            <ul class="space-y-1 text-sm mt-4 border-t pt-4 dark:border-gray-700">
                                @foreach($rawCandidateStats as $status => $count)
                                <li class="flex justify-between"><span>{{ ucfirst($status) }}</span><span class="font-semibold">{{ $count }}</span></li>
                                @endforeach
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
                                @if($employeeChart)
                                    <x-chartjs-component :chart="$employeeChart" />
                                 @else
                                    <p class="text-gray-500 dark:text-gray-400 italic">Aucune donnée employé.</p>
                                 @endif
                           </div>
                           @if($rawEmployeeStats && $rawEmployeeStats->count() > 0)
                             <ul class="space-y-1 text-sm mt-4 border-t pt-4 dark:border-gray-700">
                                  @foreach($rawEmployeeStats as $status => $count)
                                 <li class="flex justify-between"><span>{{ ucfirst($status) }}</span><span class="font-semibold">{{ $count }}</span></li>
                                 @endforeach
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
                       {{-- $periodEvents est passé par le contrôleur --}}
                      @if($periodEvents && $periodEvents->count() > 0)
                         <ul class="space-y-3">
                             @foreach($periodEvents as $event)
                             <li class="border-l-4 pl-3 {{ $event->is_absence ? ($event->css_class ? 'border-orange-400 dark:border-orange-500' : 'border-gray-400 dark:border-gray-500') : ($event->css_class ? 'border-green-400 dark:border-green-500' : 'border-blue-400 dark:border-blue-500') }}">
                                   @if($event->url)
                                       <a href="{{ $event->url }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                           <span class="font-semibold">{{ $event->employee_name }}</span> - <span class="{{ $event->css_class ?? '' }}">{{ $event->type }}</span>
                                       </a>
                                   @else
                                        <span class="font-semibold">{{ $event->employee_name }}</span> - <span class="{{ $event->css_class ?? '' }}">{{ $event->type }}</span>
                                   @endif
                                   <br>
                                   <span class="text-xs text-gray-600 dark:text-gray-400">
                                       @if($event->date->isSameDay($event->end_date))
                                           Le {{ $event->date->isoFormat('ddd D MMM YYYY') }}
                                           @if($event->date->format('H:i:s') !== '00:00:00' || $event->end_date->format('H:i:s') !== '23:59:59')
                                               (de {{ $event->date->format('H:i') }} à {{ $event->end_date->format('H:i') }})
                                           @endif
                                       @else
                                           Du {{ $event->date->isoFormat('ddd D MMM YYYY') }} au {{ $event->end_date->isoFormat('ddd D MMM YYYY') }}
                                       @endif
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
                          <a href="{{ route('admin.reports.export.employees') }}"
                             class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                              <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                              Employés Actifs (CSV)
                          </a>
                          {{-- Ajouter d'autres boutons ici --}}
                     </div>
                 </div>
             </div>

         </div> {{-- Fin max-w --}}
     </div> {{-- Fin py-12 --}}

      {{-- Pas besoin de push('scripts') si Chartjs est chargé via CDN dans config/charts.php --}}
      {{-- S'il ne l'est pas, décommente la ligne CDN dans le contrôleur ou importe Chart.js globalement via app.js/Vite --}}

 </x-app-layout>