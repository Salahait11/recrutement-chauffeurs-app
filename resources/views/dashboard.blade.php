@extends('layouts.app')

@section('content')
<div class="py-12 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-blue-900 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Message de Bienvenue --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-blue-100 dark:border-blue-800">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center">
                    <i class="fas fa-user-circle text-blue-600 dark:text-blue-400 text-3xl mr-3"></i>
                    <h2 class="text-xl font-semibold text-blue-800 dark:text-blue-300">
                        {{ __("Bienvenue, ") }} {{ $userName ?? Auth::user()->name }} !
                    </h2>
                </div>
            </div>
        </div>

        @if(Auth::user()->hasRole('admin'))
            {{-- Section Statistiques Générales --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <x-stat-card title="Candidats"
                             :stats="[
                                 ['label' => 'Nouveaux', 'value' => $candidateStats['nouveau'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                                 ['label' => 'En traitement', 'value' => $candidateStats['en_cours'], 'colorClass' => 'text-yellow-600 dark:text-yellow-400'],
                                 ['label' => 'Embauchés', 'value' => $candidateStats['embauche'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                                 ['label' => 'Refusés', 'value' => $candidateStats['refuse'], 'colorClass' => 'text-red-600 dark:text-red-400'],
                             ]"
                             :viewMoreUrl="route('candidates.index')"
                             viewMoreLabel="Gérer les candidats →" />

                <x-stat-card title="Congés"
                             :stats="[
                                 ['label' => 'En attente', 'value' => $leaveStats['en_attente'], 'colorClass' => 'text-orange-600 dark:text-orange-400'],
                                 ['label' => 'Aujourd\'hui', 'value' => $leaveStats['aujourdhui'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                                 ['label' => 'Cette semaine', 'value' => $leaveStats['cette_semaine'], 'colorClass' => 'text-indigo-600 dark:text-indigo-400'],
                             ]"
                             :viewMoreUrl="route('leave-requests.index')"
                             viewMoreLabel="Gérer les congés →" />

                <x-stat-card title="Employés"
                             :stats="[
                                 ['label' => 'Total actifs', 'value' => $employeeStats['total'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                                 ['label' => 'En congé aujourd\'hui', 'value' => $employeeStats['en_conge_aujourdhui'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                                 ['label' => 'Nouveaux ce mois', 'value' => $employeeStats['nouveaux_ce_mois'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                             ]"
                             :viewMoreUrl="route('employees.index')"
                             viewMoreLabel="Gérer les employés →" />

                 <x-stat-card title="Offres"
                             :stats="[
                                 ['label' => 'Brouillons', 'value' => $offerStats['brouillon'], 'colorClass' => 'text-gray-600 dark:text-gray-400'],
                                 ['label' => 'Envoyées', 'value' => $offerStats['envoyee'], 'colorClass' => 'text-yellow-600 dark:text-yellow-400'],
                                 ['label' => 'Acceptées', 'value' => $offerStats['acceptee'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                             ]"
                             :viewMoreUrl="route('offers.index')"
                             viewMoreLabel="Gérer les offres →" />
            </div>

            {{-- Section Activités à Venir --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Prochains Entretiens --}}
                <x-info-list-card title="Prochains Entretiens"
                                  :items="$upcomingInterviews ?? []"
                                  :viewMoreUrl="route('interviews.index')"
                                  viewMoreLabel="Voir tous les entretiens →"
                                  emptyText="Aucun entretien planifié"
                                  titleColorClass="text-blue-700 dark:text-blue-400"
                                  itemView="components.interview-item">
                </x-info-list-card>

                {{-- Prochains Tests de Conduite --}}
                <x-info-list-card title="Tests de Conduite"
                                  :items="$upcomingDrivingTests ?? []"
                                  :viewMoreUrl="route('driving-tests.index')"
                                  viewMoreLabel="Voir tous les tests →"
                                  emptyText="Aucun test planifié"
                                  titleColorClass="text-blue-700 dark:text-blue-400"
                                  itemView="components.driving-test-item">
                </x-info-list-card>

                {{-- Permis Expirant --}}
                <x-info-list-card title="⚠️ Permis Expirant"
                                  :items="$expiringLicensesCandidates ?? []"
                                  :viewMoreUrl="route('candidates.index')"
                                  viewMoreLabel="Voir tous les candidats →"
                                  emptyText="Aucun permis n'expire prochainement"
                                  titleColorClass="text-red-600 dark:text-red-400"
                                  itemView="components.expiring-license-item">
                </x-info-list-card>
            </div>

        @elseif(Auth::user()->hasRole('employee'))
            {{-- Vue Employé --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Mes Demandes en Attente --}}
                <x-info-list-card title="Mes Demandes en Attente"
                                  :items="$myPendingLeaveRequests ?? []"
                                  :viewMoreUrl="route('leave-requests.index')"
                                  viewMoreLabel="Voir toutes mes demandes →"
                                  emptyText="Aucune demande en attente"
                                  titleColorClass="text-blue-700 dark:text-blue-400"
                                  itemView="components.leave-request-item">
                </x-info-list-card>

                {{-- Historique Récent --}}
                <x-info-list-card title="Historique Récent"
                                  :items="$myRecentLeaveRequests ?? []"
                                  :viewMoreUrl="route('leave-requests.index')"
                                  viewMoreLabel="Voir tout l'historique →"
                                  emptyText="Aucun historique récent"
                                  titleColorClass="text-blue-700 dark:text-blue-400"
                                  itemView="components.leave-request-history-item">
                </x-info-list-card>
            </div>
        @endif
    </div>
</div>
@endsection