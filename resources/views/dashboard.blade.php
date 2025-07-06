@extends('layouts.app')

@section('content')
<div class="py-12 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-blue-900 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Message de Bienvenue --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-blue-100 dark:border-blue-800">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-blue-600 dark:text-blue-400 text-3xl mr-3"></i>
                        <div>
                            <h2 class="text-xl font-semibold text-blue-800 dark:text-blue-300">
                                {{ __("Bienvenue, ") }} {{ $userName ?? Auth::user()->name }} !
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ now()->format('l j F Y') }} - {{ now()->format('H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Statut système</div>
                        <div class="text-green-600 dark:text-green-400 font-semibold">● Opérationnel</div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->hasRole('admin'))
            {{-- Aperçu des Statistiques Principales --}}
            <x-stats-overview :stats="[
                ['label' => 'Candidats', 'value' => $candidateStats['total'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                ['label' => 'Employés Actifs', 'value' => $employeeStats['total'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                ['label' => 'Congés en Attente', 'value' => $leaveStats['en_attente'], 'colorClass' => 'text-orange-600 dark:text-orange-400'],
                ['label' => 'Entretiens Aujourd\'hui', 'value' => $interviewStats['aujourdhui'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
            ]" />

            {{-- Section Statistiques Détaillées --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <x-stat-card title="Candidats"
                             :stats="[
                                 ['label' => 'Nouveaux', 'value' => $candidateStats['nouveau'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                                 ['label' => 'En traitement', 'value' => $candidateStats['en_cours'], 'colorClass' => 'text-yellow-600 dark:text-yellow-400'],
                                 ['label' => 'Embauchés', 'value' => $candidateStats['embauche'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                                 ['label' => 'Refusés', 'value' => $candidateStats['refuse'], 'colorClass' => 'text-red-600 dark:text-red-400'],
                                 ['label' => 'Ce mois', 'value' => $candidateStats['nouveaux_ce_mois'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                             ]"
                             :viewMoreUrl="route('candidates.index')"
                             viewMoreLabel="Gérer les candidats →"
                             icon="fas fa-users"
                             color="blue" />

                <x-stat-card title="Employés"
                             :stats="[
                                 ['label' => 'Total actifs', 'value' => $employeeStats['total'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                                 ['label' => 'En congé aujourd\'hui', 'value' => $employeeStats['en_conge_aujourdhui'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                                 ['label' => 'Nouveaux ce mois', 'value' => $employeeStats['nouveaux_ce_mois'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                                 ['label' => 'Terminations ce mois', 'value' => $employeeStats['terminations_ce_mois'], 'colorClass' => 'text-red-600 dark:text-red-400'],
                                 ['label' => 'En congé ce mois', 'value' => $employeeStats['en_conge_ce_mois'], 'colorClass' => 'text-orange-600 dark:text-orange-400'],
                             ]"
                             :viewMoreUrl="route('employees.index')"
                             viewMoreLabel="Gérer les employés →"
                             icon="fas fa-briefcase"
                             color="green" />

                <x-stat-card title="Congés & Absences"
                             :stats="[
                                 ['label' => 'Congés en attente', 'value' => $leaveStats['en_attente'], 'colorClass' => 'text-orange-600 dark:text-orange-400'],
                                 ['label' => 'Congés aujourd\'hui', 'value' => $leaveStats['aujourdhui'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                                 ['label' => 'Absences aujourd\'hui', 'value' => $absenceStats['aujourdhui'], 'colorClass' => 'text-red-600 dark:text-red-400'],
                                 ['label' => 'Congés ce mois', 'value' => $leaveStats['ce_mois'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                                 ['label' => 'Absences ce mois', 'value' => $absenceStats['ce_mois'], 'colorClass' => 'text-gray-600 dark:text-gray-400'],
                             ]"
                             :viewMoreUrl="route('leave-requests.index')"
                             viewMoreLabel="Gérer les congés →"
                             icon="fas fa-calendar-alt"
                             color="orange" />

                <x-stat-card title="Activités"
                             :stats="[
                                 ['label' => 'Entretiens planifiés', 'value' => $interviewStats['planifies'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                                 ['label' => 'Tests de conduite', 'value' => $drivingTestStats['planifies'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                                 ['label' => 'Offres brouillons', 'value' => $offerStats['brouillon'], 'colorClass' => 'text-gray-600 dark:text-gray-400'],
                                 ['label' => 'Véhicules disponibles', 'value' => $vehicleStats['disponibles'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                                 ['label' => 'Documents ce mois', 'value' => $documentStats['ce_mois'], 'colorClass' => 'text-indigo-600 dark:text-indigo-400'],
                             ]"
                             :viewMoreUrl="route('interviews.index')"
                             viewMoreLabel="Voir les activités →"
                             icon="fas fa-chart-line"
                             color="purple" />
            </div>

            {{-- Section Alertes et Notifications --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Alertes --}}
                <div class="space-y-4">
                    @if(isset($upcomingSalaryIncreases) && $upcomingSalaryIncreases->count())
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <span class="font-semibold text-yellow-800">Augmentations de salaire</span>
                            </div>
                            <p class="mt-2 text-sm text-yellow-700">
                                {{ $upcomingSalaryIncreases->count() }} employé(s) auront une augmentation dans moins d'un mois
                            </p>
                        </div>
                    @endif

                    @if($expiringLicensesCandidates && $expiringLicensesCandidates->count() > 0)
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded shadow">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <span class="font-semibold text-red-800">Permis expirant</span>
                            </div>
                            <p class="mt-2 text-sm text-red-700">
                                {{ $expiringLicensesCandidates->count() }} permis expirent dans les 30 prochains jours
                            </p>
                        </div>
                    @endif

                    @if($vehicleStats['en_maintenance'] > 0)
                        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded shadow">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <span class="font-semibold text-orange-800">Véhicules en maintenance</span>
                            </div>
                            <p class="mt-2 text-sm text-orange-700">
                                {{ $vehicleStats['en_maintenance'] }} véhicule(s) en maintenance
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Statistiques Rapides --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Statistiques Rapides</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Taux d'embauche</span>
                                <span class="text-lg font-bold text-green-600">
                                    {{ $candidateStats['total'] > 0 ? round(($candidateStats['embauche'] / $candidateStats['total']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Taux d'acceptation offres</span>
                                <span class="text-lg font-bold text-blue-600">
                                    {{ $offerStats['total'] > 0 ? round(($offerStats['acceptee'] / $offerStats['total']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Taux d'approbation congés</span>
                                <span class="text-lg font-bold text-purple-600">
                                    {{ $leaveStats['ce_mois'] > 0 ? round(($leaveStats['approuves_ce_mois'] / $leaveStats['ce_mois']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Disponibilité véhicules</span>
                                <span class="text-lg font-bold text-indigo-600">
                                    {{ $vehicleStats['total'] > 0 ? round(($vehicleStats['disponibles'] / $vehicleStats['total']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
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
                                  titleColorClass="text-green-700 dark:text-green-400"
                                  itemView="components.driving-test-item">
                </x-info-list-card>

                {{-- Absences Récentes --}}
                <x-info-list-card title="Absences Récentes"
                                  :items="$recentAbsences ?? []"
                                  :viewMoreUrl="route('admin.absences.index')"
                                  viewMoreLabel="Voir toutes les absences →"
                                  emptyText="Aucune absence récente"
                                  titleColorClass="text-red-700 dark:text-red-400"
                                  itemView="components.absence-item">
                </x-info-list-card>
            </div>

        @elseif(Auth::user()->hasRole('manager'))
            {{-- Vue Manager --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <x-stat-card title="Candidats"
                             :stats="[
                                 ['label' => 'Nouveaux', 'value' => $candidateStats['nouveau'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                                 ['label' => 'En traitement', 'value' => $candidateStats['en_cours'], 'colorClass' => 'text-yellow-600 dark:text-yellow-400'],
                                 ['label' => 'Embauchés', 'value' => $candidateStats['embauche'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                                 ['label' => 'Ce mois', 'value' => $candidateStats['nouveaux_ce_mois'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                             ]"
                             :viewMoreUrl="route('candidates.index')"
                             viewMoreLabel="Gérer les candidats →"
                             icon="fas fa-users"
                             color="blue" />

                <x-stat-card title="Congés"
                             :stats="[
                                 ['label' => 'En attente', 'value' => $leaveStats['en_attente'], 'colorClass' => 'text-orange-600 dark:text-orange-400'],
                                 ['label' => 'Aujourd\'hui', 'value' => $leaveStats['aujourdhui'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                                 ['label' => 'Cette semaine', 'value' => $leaveStats['cette_semaine'], 'colorClass' => 'text-indigo-600 dark:text-indigo-400'],
                                 ['label' => 'Ce mois', 'value' => $leaveStats['ce_mois'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                             ]"
                             :viewMoreUrl="route('leave-requests.index')"
                             viewMoreLabel="Gérer les congés →"
                             icon="fas fa-calendar-alt"
                             color="orange" />

                <x-stat-card title="Entretiens"
                             :stats="[
                                 ['label' => 'Planifiés', 'value' => $interviewStats['planifies'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                                 ['label' => 'Aujourd\'hui', 'value' => $interviewStats['aujourdhui'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                                 ['label' => 'Cette semaine', 'value' => $interviewStats['cette_semaine'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                             ]"
                             :viewMoreUrl="route('interviews.index')"
                             viewMoreLabel="Voir les entretiens →"
                             icon="fas fa-handshake"
                             color="blue" />

                <x-stat-card title="Tests de Conduite"
                             :stats="[
                                 ['label' => 'Planifiés', 'value' => $drivingTestStats['planifies'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                                 ['label' => 'Aujourd\'hui', 'value' => $drivingTestStats['aujourdhui'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                                 ['label' => 'Cette semaine', 'value' => $drivingTestStats['cette_semaine'], 'colorClass' => 'text-purple-600 dark:text-purple-400'],
                             ]"
                             :viewMoreUrl="route('driving-tests.index')"
                             viewMoreLabel="Voir les tests →"
                             icon="fas fa-car"
                             color="green" />
            </div>

            {{-- Section Activités à Venir --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

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
                                  titleColorClass="text-green-700 dark:text-green-400"
                                  itemView="components.driving-test-item">
                </x-info-list-card>
            </div>

        @elseif(Auth::user()->hasRole('employee'))
            {{-- Vue Employé --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <x-stat-card title="Mes Congés"
                             :stats="[
                                 ['label' => 'En attente', 'value' => $myLeaveStats['en_attente'], 'colorClass' => 'text-orange-600 dark:text-orange-400'],
                                 ['label' => 'Approuvés ce mois', 'value' => $myLeaveStats['approuves_ce_mois'], 'colorClass' => 'text-green-600 dark:text-green-400'],
                                 ['label' => 'Total ce mois', 'value' => $myLeaveStats['total_ce_mois'], 'colorClass' => 'text-blue-600 dark:text-blue-400'],
                             ]"
                             :viewMoreUrl="route('leave-requests.index')"
                             viewMoreLabel="Voir mes congés →"
                             icon="fas fa-calendar-alt"
                             color="blue" />

                <x-stat-card title="Mes Absences"
                             :stats="[
                                 ['label' => 'Ce mois', 'value' => $myAbsenceStats['ce_mois'], 'colorClass' => 'text-red-600 dark:text-red-400'],
                                 ['label' => 'Total', 'value' => $myAbsenceStats['total'], 'colorClass' => 'text-gray-600 dark:text-gray-400'],
                             ]"
                             :viewMoreUrl="route('admin.absences.index')"
                             viewMoreLabel="Voir mes absences →"
                             icon="fas fa-clock"
                             color="red" />

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Actions Rapides</h3>
                        <div class="space-y-3">
                            <a href="{{ route('leave-requests.create') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                                Demander un congé
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block w-full text-center bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                                Modifier mon profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

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
                                  titleColorClass="text-gray-700 dark:text-gray-400"
                                  itemView="components.leave-request-history-item">
                </x-info-list-card>
            </div>
        @endif
    </div>
</div>
@endsection