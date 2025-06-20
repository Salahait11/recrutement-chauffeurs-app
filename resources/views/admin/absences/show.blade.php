@extends('layouts.app')

@section('title', 'Détails de l\'Absence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                {{-- En-tête avec titre et boutons d'action --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Détails de l'Absence
                    </h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.absences.edit', $absence->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </a>
                        <a href="{{ route('admin.absences.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Retour
                        </a>
                    </div>
                </div>

                {{-- Statut de l'absence --}}
                <div class="mb-6">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $absence->is_justified ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                        {{ $absence->is_justified ? 'Absence Justifiée' : 'Absence Non Justifiée' }}
                    </span>
                </div>

                {{-- Informations principales --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Informations de l'employé --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2">
                                Informations de l'Employé
                            </h3>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nom de l'employé</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $absence->employee->user->name ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Matricule</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $absence->employee->matricule ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Détails de l'absence --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2">
                                Détails de l'Absence
                            </h3>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Date</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $absence->absence_date->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Heure de début</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $absence->start_time ? \Carbon\Carbon::parse($absence->start_time)->format('H:i') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Heure de fin</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $absence->end_time ? \Carbon\Carbon::parse($absence->end_time)->format('H:i') : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informations supplémentaires --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Motif et notes --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2">
                                Motif et Notes
                            </h3>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Type de motif</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $absence->reason_type ?? 'Non spécifié' }}
                                    </p>
                                </div>
                                @if($absence->notes)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notes</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">
                                        {{ $absence->notes }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Informations administratives --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2">
                                Informations Administratives
                            </h3>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Enregistré par</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $absence->recorder->name ?? 'Système' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Date d'enregistrement</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $absence->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                @if($absence->updated_at != $absence->created_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dernière modification</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $absence->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 