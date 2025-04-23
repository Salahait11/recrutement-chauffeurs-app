@props(['item'])

<div class="flex justify-between items-start gap-2">
    <div>
        <a href="{{ route('interviews.show', $item->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
            {{ $item->candidate->first_name }} {{ $item->candidate->last_name }}
        </a>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->interview_date->format('d/m/Y H:i') }}</p>
    </div>
    <x-badge :color="$item->type === 'technique' ? 'blue' : 'green'" class="flex-shrink-0">
        {{ $item->type === 'technique' ? 'Technique' : 'RH' }}
    </x-badge>
</div> 