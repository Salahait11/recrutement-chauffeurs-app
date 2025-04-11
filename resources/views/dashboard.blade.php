{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de Bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Message de Bienvenue --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bienvenue, ") }} {{ $userName ?? Auth::user()->name }} !
                </div>
            </div>

            @if(Auth::user()->isAdmin())
                {{-- Section Statistiques Générales --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    {{-- Stats Candidats --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Candidats</h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Nouveaux</span>
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $candidateStats['nouveau'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">En traitement</span>
                                    <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $candidateStats['en_cours'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Embauchés</span>
                                    <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $candidateStats['embauche'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Refusés</span>
                                    <span class="text-lg font-bold text-red-600 dark:text-red-400">{{ $candidateStats['refuse'] }}</span>
                                </div>
                            </div>
                            <a href="{{ route('candidates.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Gérer les candidats →</a>
                        </div>
                    </div>

                    {{-- Stats Congés --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Congés</h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">En attente</span>
                                    <span class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $leaveStats['en_attente'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Aujourd'hui</span>
                                    <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $leaveStats['aujourdhui'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Cette semaine</span>
                                    <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $leaveStats['cette_semaine'] }}</span>
                                </div>
                            </div>
                            <a href="{{ route('leave-requests.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Gérer les congés →</a>
                        </div>
                    </div>

                    {{-- Stats Employés --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Employés</h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total actifs</span>
                                    <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $employeeStats['total'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">En congé aujourd'hui</span>
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $employeeStats['en_conge_aujourdhui'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Nouveaux ce mois</span>
                                    <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $employeeStats['nouveaux_ce_mois'] }}</span>
                                </div>
                            </div>
                            <a href="{{ route('employees.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Gérer les employés →</a>
                        </div>
                    </div>

                    {{-- Stats Offres --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Offres</h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Brouillons</span>
                                    <span class="text-lg font-bold text-gray-600 dark:text-gray-400">{{ $offerStats['brouillon'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Envoyées</span>
                                    <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $offerStats['envoyee'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Acceptées</span>
                                    <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $offerStats['acceptee'] }}</span>
                                </div>
                            </div>
                            <a href="{{ route('offers.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Gérer les offres →</a>
                        </div>
                    </div>
                </div>

                {{-- Section Activités à Venir --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Prochains Entretiens --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Prochains Entretiens</h3>
                            @forelse($upcomingInterviews as $interview)
                                <div class="mb-3 pb-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('interviews.show', $interview->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                                {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                                            </a>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $interview->interview_date->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $interview->type === 'technique' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' }}">
                                            {{ $interview->type === 'technique' ? 'Technique' : 'RH' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-sm italic">Aucun entretien planifié</p>
                            @endforelse
                            <a href="{{ route('interviews.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Voir tous les entretiens →</a>
                        </div>
                    </div>

                    {{-- Prochains Tests de Conduite --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Tests de Conduite</h3>
                            @forelse($upcomingDrivingTests as $test)
                                <div class="mb-3 pb-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('driving-tests.show', $test->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                                {{ $test->candidate->first_name }} {{ $test->candidate->last_name }}
                                            </a>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $test->test_date->format('d/m/Y H:i') }}</p>
                                            @if($test->vehicle)
                                                <p class="text-xs text-gray-500 dark:text-gray-500">Véhicule: {{ $test->vehicle->plate_number }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-sm italic">Aucun test planifié</p>
                            @endforelse
                            <a href="{{ route('driving-tests.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Voir tous les tests →</a>
                        </div>
                    </div>

                    {{-- Permis Expirant --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">⚠️ Permis Expirant</h3>
                            @forelse($expiringLicensesCandidates as $candidate)
                                <div class="mb-3 pb-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                    <div>
                                        <a href="{{ route('candidates.show', $candidate->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                            {{ $candidate->first_name }} {{ $candidate->last_name }}
                                        </a>
                                        <p class="text-sm text-red-600 dark:text-red-400">
                                            Expire le {{ $candidate->driving_license_expiry->format('d/m/Y') }}
                                            <span class="text-xs text-gray-500 dark:text-gray-500">({{ $candidate->driving_license_expiry->diffForHumans() }})</span>
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-sm italic">Aucun permis n'expire prochainement</p>
                            @endforelse
                            <a href="{{ route('candidates.index') }}" class="mt-4 inline-block text-sm text-blue-500 hover:underline">Voir tous les candidats →</a>
                        </div>
                    </div>
                </div>

            @elseif(Auth::user()->isEmployee())
                {{-- Vue Employé --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Mes Demandes en Attente --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Mes Demandes en Attente</h3>
                            @forelse($myPendingLeaveRequests as $request)
                                <div class="mb-3 pb-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                    <a href="{{ route('leave-requests.show', $request->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $request->reason }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-sm italic">Aucune demande en attente</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Historique Récent --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Historique Récent</h3>
                            @forelse($myRecentLeaveRequests as $request)
                                <div class="mb-3 pb-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('leave-requests.show', $request->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}
                                            </a>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $request->reason }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($request->status === 'approuve') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif($request->status === 'refuse') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-sm italic">Aucun historique récent</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>