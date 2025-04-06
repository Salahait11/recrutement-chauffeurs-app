{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de Bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bienvenue, ") }} {{ $userName ?? Auth::user()->name }} !
                </div>
            </div>

            {{-- Section pour Admin / RH / Manager --}}
            @if(Auth::user()->isAdmin() || Auth::user()->hasRole('recruiter') || Auth::user()->hasRole('manager') || Auth::user()->hasRole('rh_manager'))
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    {{-- Carte Nouveaux Candidats --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Nouveaux Candidats</h3>
                            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">{{ $newCandidatesCount ?? 0 }}</p>
                            <a href="{{ route('candidates.index', ['status' => 'new']) }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Voir la liste</a>
                        </div>
                    </div>

                     {{-- Carte Demandes Congé en Attente --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Demandes de Congé</h3>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ $pendingLeaveRequestsCount ?? 0 }} en attente</p>
                             <a href="{{ route('leave-requests.index', ['status' => 'pending']) }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Voir les demandes</a>
                        </div>
                    </div>

                     {{-- Carte Prochains Entretiens --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:col-span-2 lg:col-span-1">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Prochains Entretiens</h3>
                            @forelse($upcomingInterviews ?? [] as $interview)
                                <div class="text-sm mb-2 border-b dark:border-gray-700 pb-1">
                                     <a href="{{ route('interviews.show', $interview->id) }}" class="text-blue-500 hover:underline">
                                        {{ $interview->candidate->first_name ?? '?' }} {{ $interview->candidate->last_name ?? '?' }}
                                     </a><br>
                                     <span class="text-gray-600 dark:text-gray-400">{{ $interview->interview_date->format('d/m/Y H:i') }}</span>
                                     <span class="text-gray-500 dark:text-gray-500"> ({{ $interview->type ?? 'Entretien' }})</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">Aucun entretien planifié.</p>
                            @endforelse
                            <a href="{{ route('interviews.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Voir tous</a>
                        </div>
                    </div>

                    {{-- Carte Prochains Tests Conduite --}}
                    {{-- <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Prochains Tests Conduite</h3>
                             @forelse($upcomingDrivingTests ?? [] as $test)
                                <div class="text-sm mb-2 border-b dark:border-gray-700 pb-1">
                                     <a href="{{ route('driving-tests.show', $test->id) }}" class="text-blue-500 hover:underline">
                                        {{ $test->candidate->first_name ?? '?' }} {{ $test->candidate->last_name ?? '?' }}
                                     </a><br>
                                     <span class="text-gray-600 dark:text-gray-400">{{ $test->test_date->format('d/m/Y H:i') }}</span>
                                     @if($test->vehicle) <span class="text-gray-500 dark:text-gray-500"> ({{ $test->vehicle->plate_number }})</span> @endif
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">Aucun test planifié.</p>
                            @endforelse
                            <a href="{{ route('driving-tests.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Voir tous</a>
                        </div>
                    </div> --}}

                    {{-- Ajouter d'autres cartes ici --}}

                </div>
            @endif

            {{-- Section pour Employé Standard --}}
             @if(Auth::user()->isEmployee())
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                     {{-- Carte Mes Demandes en Attente --}}
                     <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Mes Demandes de Congé en Attente</h3>
                            @forelse($myPendingLeaveRequests ?? [] as $leaveRequest)
                                <div class="text-sm mb-2 border-b dark:border-gray-700 pb-1">
                                     <a href="{{ route('leave-requests.show', $leaveRequest->id) }}" class="text-blue-500 hover:underline">
                                         {{ $leaveRequest->leaveType->name ?? 'Demande' }} du {{ $leaveRequest->start_date->format('d/m/Y') }}
                                     </a><br>
                                     <span class="text-gray-600 dark:text-gray-400">Soumise le: {{ $leaveRequest->created_at->format('d/m/Y') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">Aucune demande en attente.</p>
                            @endforelse
                             <a href="{{ route('leave-requests.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Voir toutes mes demandes</a>
                        </div>
                    </div>

                    {{-- Carte Mes Dernières Demandes Traitées --}}
                     <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                             <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Historique Récent</h3>
                              @forelse($myRecentLeaveRequests ?? [] as $leaveRequest)
                                <div class="text-sm mb-2 border-b dark:border-gray-700 pb-1">
                                     <a href="{{ route('leave-requests.show', $leaveRequest->id) }}" class="text-blue-500 hover:underline">
                                         {{ $leaveRequest->leaveType->name ?? 'Demande' }} du {{ $leaveRequest->start_date->format('d/m/Y') }}
                                     </a><br>
                                      <span class="px-1 inline-flex text-xs leading-5 font-semibold rounded-full {{-- couleurs statut --}} ...">
                                        {{ ucfirst($leaveRequest->status) }}
                                     </span>
                                     <span class="text-gray-600 dark:text-gray-400"> - Traitée le: {{ optional($leaveRequest->approved_at)->format('d/m/Y') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">Aucune demande récente.</p>
                            @endforelse
                             <a href="{{ route('leave-requests.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Voir toutes mes demandes</a>
                        </div>
                    </div>

                </div>
             @endif

        </div>
    </div>
</x-app-layout>