{{-- resources/views/candidates/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Messages d'erreur -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- En-tête avec titre -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Détails du candidat
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3">
                    <a href="{{ route('candidates.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la liste
                    </a>
                    <a href="{{ route('candidates.edit', $candidate) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
                    </a>
                    <a href="{{ route('offers.createForCandidate', $candidate) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Faire une offre
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations du candidat -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6 space-y-6">
            <!-- Informations personnelles -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations personnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prénom</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $candidate->first_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $candidate->last_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $candidate->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphone</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $candidate->phone }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de naissance</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $candidate->birth_date->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $candidate->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Informations professionnelles -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Informations professionnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro de permis</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $candidate->driving_license_number }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date d'expiration du permis</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $candidate->driving_license_expiry->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Années d'expérience</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $candidate->years_of_experience }} ans</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($candidate->status === 'embauche')
                                    bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($candidate->status === 'refuse')
                                    bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @else
                                    bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                @endif
                            ">
                                {{ $statusLabels[$candidate->status] }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($candidate->notes)
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Notes</h3>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $candidate->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Section Documents -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between mb-4">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Documents</h3>
                <form action="{{ route('candidates.documents.store', $candidate) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                    @csrf
                    <input type="file" name="document" required class="block text-sm text-gray-500 dark:text-gray-400">
                    <input type="text" name="document_type" value="{{ old('document_type') }}" placeholder="Type" class="block text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1">
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none">Ajouter</button>
                </form>
            </div>
            @if(session('document_success'))<div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('document_success') }}</div>@endif
            @if(session('document_error'))<div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">{{ session('document_error') }}</div>@endif
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom du fichier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Taille</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($candidate->documents as $doc)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ Storage::disk('public')->url($doc->file_path) }}" target="_blank" class="text-blue-600 hover:underline font-medium">{{ $doc->original_name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('documents.update', $doc) }}" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="document_type" value="{{ $doc->type }}" placeholder="Type" class="block w-full bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1">
                                    <button type="submit" class="text-blue-600 hover:text-blue-800">OK</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ round($doc->size/1024,2) }} KB</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $doc->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Supprimer ce document ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Aucun document.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
