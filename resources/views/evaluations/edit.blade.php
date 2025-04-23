@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            @if(isset($interview))
                Modifier l'évaluation de l'entretien
            @else
                Modifier l'évaluation du test de conduite
            @endif
        </h1>
        <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('evaluations.update', $evaluation) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informations du candidat -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Informations du candidat</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Nom du candidat</p>
                        <p class="font-medium">{{ $evaluation->candidate->getFullName() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Date</p>
                        <p class="font-medium">{{ isset($interview) ? $interview->date->format('d/m/Y H:i') : $drivingTest->test_date->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Critères d'évaluation -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Critères d'évaluation</h2>
                @foreach($criteria as $criterion)
                    <div class="mb-4 p-4 border rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-medium">{{ $criterion->name }}</h3>
                            <span class="text-sm text-gray-500">{{ $criterion->category }}</span>
                        </div>
                        <input type="hidden" name="responses[{{ $criterion->id }}][criterion_id]" value="{{ $criterion->id }}">
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                            <select name="responses[{{ $criterion->id }}][rating]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $evaluation->responses->where('criterion_id', $criterion->id)->first()?->rating == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Commentaires</label>
                            <textarea name="responses[{{ $criterion->id }}][comments]" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $evaluation->responses->where('criterion_id', $criterion->id)->first()?->comments }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Recommandation -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Recommandation</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="recommendation" value="positive" {{ $evaluation->recommendation === 'positive' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span class="text-gray-700">Positive</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="recommendation" value="neutral" {{ $evaluation->recommendation === 'neutral' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span class="text-gray-700">Neutre</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="recommendation" value="negative" {{ $evaluation->recommendation === 'negative' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span class="text-gray-700">Négative</span>
                    </label>
                </div>
            </div>

            <!-- Conclusion -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Conclusion</h2>
                <textarea name="conclusion" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $evaluation->conclusion }}</textarea>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4">
                <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Annuler
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
