@props(['item'])

<div class="flex justify-between items-start">
    <div>
        <a href="{{ route('driving-tests.show', $item->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
            {{ $item->candidate->first_name }} {{ $item->candidate->last_name }}
        </a>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->test_date->format('d/m/Y H:i') }}</p>
        @if($item->vehicle)
            <p class="text-xs text-gray-500 dark:text-gray-500">Véhicule: {{ $item->vehicle->plate_number }}</p>
        @endif
    </div>
</div> 