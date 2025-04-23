@props(['item'])

<div class="flex justify-between items-start gap-2">
    <div>
        <a href="{{ route('leave-requests.show', $item->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
            {{ $item->start_date->format('d/m/Y') }} - {{ $item->end_date->format('d/m/Y') }}
        </a>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $item->reason }}</p>
    </div>
    <x-badge :color="match($item->status) {
        'approuve' => 'green',
        'refuse' => 'red',
        default => 'gray'
    }" class="flex-shrink-0 mt-0.5">
        {{ ucfirst($item->status) }}
    </x-badge>
</div> 