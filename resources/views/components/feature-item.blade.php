{{-- resources/views/components/feature-item.blade.php --}}
@props(['icon' => 'check'])

<li class="flex items-start gap-3 py-1">
    <span class="flex-shrink-0 mt-1 text-blue-500 dark:text-blue-400">
        @if($icon === 'check')
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        @else
            {{-- Fallback ou autre icône si nécessaire --}}
            <span class="w-4 h-4 inline-block"></span> {{-- Placeholder --}}
        @endif
    </span>
    <span class="text-sm text-text-secondary dark:text-secondary-dark">
        {{ $slot }}
    </span>
</li> 