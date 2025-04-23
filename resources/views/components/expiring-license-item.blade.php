@props(['item'])

<div>
    <a href="{{ route('candidates.show', $item->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
        {{ $item->first_name }} {{ $item->last_name }}
    </a>
    <p class="text-sm text-red-600 dark:text-red-400">
        Expire le {{ $item->driving_license_expiry->format('d/m/Y') }}
        <span class="text-xs text-gray-500 dark:text-gray-500">({{ $item->driving_license_expiry->diffForHumans() }})</span>
    </p>
</div> 