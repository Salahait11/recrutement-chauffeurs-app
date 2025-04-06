{{-- resources/views/leave_requests/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails Demande de Congé #') }}{{ $leaveRequest->id }}
            </h2>
            <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center ...">{{ __('Retour à la liste') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Messages Flash --}}
                     @if (session('success')) <div class="mb-4 bg-green-100 ...">{{ session('success') }}</div> @endif
                     @if (session('error')) <div class="mb-4 bg-red-100 ...">{{ session('error') }}</div> @endif

                    {{-- Détails de la demande --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="md:col-span-1 font-semibold ...">{{ __('Employé') }}</div>
                        <div class="md:col-span-2">{{ $leaveRequest->employee->user->name ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold ...">{{ __('Type de Congé') }}</div>
                        <div class="md:col-span-2">{{ $leaveRequest->leaveType->name ?? 'N/A' }}</div>

                        <div class="md:col-span-1 font-semibold ...">{{ __('Date Début') }}</div>
                        <div class="md:col-span-2">{{ $leaveRequest->start_date->format('d/m/Y H:i') }}</div>

                        <div class="md:col-span-1 font-semibold ...">{{ __('Date Fin') }}</div>
                        <div class="md:col-span-2">{{ $leaveRequest->end_date->format('d/m/Y H:i') }}</div>

                        <div class="md:col-span-1 font-semibold ...">{{ __('Durée (jours)') }}</div>
                        <div class="md:col-span-2">{{ $leaveRequest->duration_days ?? 'N/C' }}</div>

                        <div class="md:col-span-1 font-semibold ... pt-1">{{ __('Motif / Commentaires') }}</div>
                        <div class="md:col-span-2 whitespace-pre-wrap bg-gray-50 dark:bg-gray-900 p-2 rounded border ...">{{ $leaveRequest->reason ?? 'Aucun' }}</div>

                        <div class="md:col-span-1 font-semibold ...">{{ __('Justificatif') }}</div>
                        <div class="md:col-span-2">
                            @if($leaveRequest->attachment_path)
                                <a href="{{ Storage::url($leaveRequest->attachment_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                    Voir le justificatif
                                </a>
                            @else
                                Aucun
                            @endif
                        </div>

                        <div class="md:col-span-1 font-semibold ...">{{ __('Statut') }}</div>
                        <div class="md:col-span-2">
                             <span class="px-2 inline-flex text-lg leading-5 font-semibold rounded-full {{-- Ajuste les couleurs/classes si besoin --}}
                                @switch($leaveRequest->status)
                                    @case('approved') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break
                                    @case('rejected')
                                    @case('canceled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                    @default bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 {{-- pending --}}
                                @endswitch
                            ">
                                {{ ucfirst($leaveRequest->status) }}
                            </span>
                        </div>

                        @if($leaveRequest->status === 'approved' || $leaveRequest->status === 'rejected')
                            <div class="md:col-span-1 font-semibold ...">{{ __('Traité par') }}</div>
                            <div class="md:col-span-2">{{ $leaveRequest->approver->name ?? 'N/A' }} le {{ optional($leaveRequest->approved_at)->format('d/m/Y H:i') }}</div>

                            <div class="md:col-span-1 font-semibold ... pt-1">{{ __('Commentaire Approbateur') }}</div>
                            <div class="md:col-span-2 whitespace-pre-wrap bg-gray-50 dark:bg-gray-900 p-2 rounded border ...">{{ $leaveRequest->approver_comment ?? 'Aucun' }}</div>
                        @endif

                        <div class="md:col-span-1 font-semibold ...">{{ __('Date Soumission') }}</div>
                        <div class="md:col-span-2">{{ $leaveRequest->created_at->format('d/m/Y H:i') }}</div>

                    </div>

                    {{-- Section Approbation / Rejet (visible seulement pour Manager/RH et si statut = pending) --}}
                    {{-- !! Remplacer la condition par la vraie logique de rôles/permissions !! --}}
                    {{-- @can('approve', $leaveRequest) --}}
                    @if($leaveRequest->status === 'pending') {{-- Condition simplifiée pour l'instant --}}
                        <hr class="dark:border-gray-700 my-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Action Requise (Approbation / Rejet)
                        </h3>
                        <form method="POST" action="{{ route('leave-requests.update', $leaveRequest->id) }}">
                            @csrf
                            @method('PUT') {{-- Ou PATCH --}}

                            <div class="space-y-4">
                                <div>
                                    <label for="approver_comment" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Commentaire (Requis pour rejet)') }}</label>
                                    <textarea name="approver_comment" id="approver_comment" rows="3" class="block mt-1 w-full rounded-md ...">{{ old('approver_comment') }}</textarea>
                                </div>

                                <div class="flex items-center justify-end space-x-3">
                                    {{-- Bouton Rejeter --}}
                                    <button type="submit" name="action" value="reject" class="inline-flex items-center px-4 py-2 bg-red-600 ...">
                                        Rejeter
                                    </button>
                                    {{-- Bouton Approuver --}}
                                    <button type="submit" name="action" value="approve" class="inline-flex items-center px-4 py-2 bg-green-600 ...">
                                        Approuver
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    {{-- @endcan --}}

                     {{-- Bouton Annuler pour l'employé si 'pending' --}}
                     @if($leaveRequest->status === 'pending' /* && Auth::id() == $leaveRequest->employee->user_id */)
                        <hr class="dark:border-gray-700 my-6">
                        <div class="flex justify-end">
                             <form action="{{ route('leave-requests.destroy', $leaveRequest->id) }}" method="POST" onsubmit="return confirm('Annuler cette demande ?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-500 ...">Annuler ma demande</button>
                            </form>
                        </div>
                     @endif

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>