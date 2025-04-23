{{-- resources/views/leave_requests/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Messages Flash & Validation Errors -->
    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p> </div> </div> </div>
    @endif
    @if (session('error'))
        <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p> </div> </div> </div>
    @endif
    @if ($errors->any())
        <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800">
            <div class="flex">
                <div class="flex-shrink-0"> <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /> </svg> </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Erreur de validation !</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                        <ul role="list" class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- En-tête avec titre et actions principales -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône Calendar Check (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0h18M-4.5 12h22.5m-6.375-2.625a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm-3.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            Détails Demande de Congé #{{ $leaveRequest->id }}
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex flex-shrink-0 md:ml-4 md:mt-0 space-x-3">
                    <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Retour à la liste
                    </a>
                    {{-- Bouton Modifier (Rarement applicable à une demande soumise) --}}
                    {{-- @can('update', $leaveRequest) --}}
                    {{-- @if($leaveRequest->status === \App\Models\LeaveRequest::STATUS_EN_ATTENTE) --}}
                    {{-- <a href="{{ route('leave-requests.edit', $leaveRequest->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Modifier
                    </a> --}}
                    {{-- @endif --}}
                    {{-- @endcan --}}

                    {{-- Bouton Annuler (Supprimer) --}}
                    {{-- !! Adapter conditions de permission/statut !! --}}
                    @if($leaveRequest->status === \App\Models\LeaveRequest::STATUS_EN_ATTENTE && (Auth::user()->isAdmin() || Auth::id() == $leaveRequest->employee?->user_id) )
                         <form action="{{ route('leave-requests.destroy', $leaveRequest->id) }}" method="POST" class="inline" onsubmit="return confirm('Voulez-vous vraiment annuler cette demande ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Annuler la demande
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bloc Principal d'Informations -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6 space-y-6">
            <!-- Détails de la Demande -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Détails de la Demande</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employé</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $leaveRequest->employee->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type de Congé</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $leaveRequest->leaveType->name ?? 'N/A' }}</p>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date Début</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $leaveRequest->start_date->format('d/m/Y H:i') }}</p>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date Fin</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $leaveRequest->end_date->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durée (jours)</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $leaveRequest->duration_days ?? 'N/C' }}</p>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
                        <p class="mt-1">
                             @php
                                $statusLabel = $leaveRequest->statuses[$leaveRequest->status] ?? ucfirst($leaveRequest->status);
                                $statusClass = '';
                                switch($leaveRequest->status) {
                                    case 'approuve': $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'; break;
                                    case 'refuse': $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'; break;
                                    case 'annule': $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'; break;
                                    case 'pending': default: $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; break;
                                }
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </p>
                    </div>
                    <div class="md:col-span-2"> {{-- Prend toute la largeur --}}
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motif / Commentaires</label>
                        @if($leaveRequest->reason)
                            <div class="mt-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $leaveRequest->reason }}</p>
                            </div>
                        @else
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 italic">Aucun</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Justificatif</label>
                        <p class="mt-1 text-sm">
                            @if($leaveRequest->attachment_path)
                                <a href="{{ Storage::url($leaveRequest->attachment_path) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center">
                                     <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.122 2.122l7.81-7.81" /></svg>
                                    Voir le justificatif
                                </a>
                            @else
                                <span class="text-gray-500 dark:text-gray-400 italic">Aucun</span>
                            @endif
                        </p>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date Soumission</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $leaveRequest->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Détails d'Approbation (si applicable) --}}
            @if($leaveRequest->status === \App\Models\LeaveRequest::STATUS_APPROUVE || $leaveRequest->status === \App\Models\LeaveRequest::STATUS_REFUSE)
                <hr class="dark:border-gray-700">
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Détails d'Approbation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Traité par</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $leaveRequest->approver->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de traitement</label>
                             <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ optional($leaveRequest->approved_at)->format('d/m/Y H:i') ?? '-' }}</p>
                        </div>
                         <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commentaire Approbateur</label>
                            @if($leaveRequest->approver_comment)
                                <div class="mt-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $leaveRequest->approver_comment }}</p>
                                </div>
                            @else
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 italic">Aucun</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Section Approbation / Rejet (visible si statut pending et autorisé) --}}
            {{-- !! Adapter la condition @can !! --}}
             @if($leaveRequest->status === \App\Models\LeaveRequest::STATUS_EN_ATTENTE /* && Auth::user()->can('approve', $leaveRequest) */)
                <hr class="dark:border-gray-700">
                 <div class="space-y-4">
                    <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Action Requise (Approbation / Rejet)</h3>
                     <form method="POST" action="{{ route('leave-requests.update', $leaveRequest->id) }}">
                        @csrf
                        @method('PUT') {{-- Ou PATCH --}}

                        <div class="space-y-4">
                            <div>
                                <label for="approver_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commentaire (Optionnel pour approbation, requis pour rejet)</label>
                                <textarea name="approver_comment" id="approver_comment" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm"
                                          placeholder="Ajouter un commentaire...">{{ old('approver_comment') }}</textarea>
                                 @error('approver_comment') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center justify-end space-x-3">
                                {{-- Bouton Rejeter --}}
                                <button type="submit" name="action" value="reject" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                     <svg class="-ml-0.5 mr-1.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" /></svg>
                                    Rejeter
                                </button>
                                {{-- Bouton Approuver --}}
                                <button type="submit" name="action" value="approve" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                     <svg class="-ml-0.5 mr-1.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>
                                    Approuver
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

        </div> {{-- Fin p-6 --}}
    </div> {{-- Fin bg-white --}}

</div> {{-- Fin space-y-6 --}}
@endsection