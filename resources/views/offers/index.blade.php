@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-300">
                    {{ __('Gestion des Offres') }}
                </h2>
                
            </div>
        </div>

        <!-- Message de succès -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filtres -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('offers.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Filtre par candidat -->
                        <div>
                            <label for="candidate_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Candidat') }}
                            </label>
                            <select name="candidate_id" id="candidate_id" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                <option value="">{{ __('Tous les candidats') }}</option>
                                @foreach($candidatesWithOffers as $candidate)
                                    <option value="{{ $candidate->id }}" {{ request('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                        {{ $candidate->first_name }} {{ $candidate->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtre par statut -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Statut') }}
                            </label>
                            <select name="status" id="status" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                <option value="">{{ __('Tous les statuts') }}</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ __(ucfirst($status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtre par date de début -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Date de début') }}
                            </label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                        </div>

                        <!-- Filtre par date de fin -->
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Date de fin') }}
                            </label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Filtrer') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des offres -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($offers->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                        {{ __('Aucune offre trouvée.') }}
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Candidat') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Poste') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Salaire') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Statut') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($offers as $offer)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $offer->position_offered }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ number_format($offer->salary, 2) }} € / {{ $offer->salary_period }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($offer->status === \App\Models\Offer::STATUS_BROUILLON) bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                                @elseif($offer->status === \App\Models\Offer::STATUS_ENVOYEE) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                                @elseif($offer->status === \App\Models\Offer::STATUS_ACCEPTEE) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($offer->status === \App\Models\Offer::STATUS_REFUSEE) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @elseif($offer->status === \App\Models\Offer::STATUS_EXPIREE) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                                @endif">
                                                {{ __(ucfirst($offer->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $offer->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('offers.show', $offer) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                                {{ __('Voir') }}
                                            </a>
                                            @if($offer->status === 'brouillon')
                                                <a href="{{ route('offers.edit', $offer) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                    {{ __('Modifier') }}
                                                </a>
                                                <form action="{{ route('offers.destroy', $offer) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette offre ?') }}')">
                                                        {{ __('Supprimer') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $offers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection