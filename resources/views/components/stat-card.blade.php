@props([
    'title',
    'stats', // Attendu comme un tableau associatif ou une collection: [['label' => '...', 'value' => '...', 'colorClass' => 'text-blue-600 dark:text-blue-400'], ...]
    'viewMoreUrl',
    'viewMoreLabel' => 'Gérer →'
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-blue-100 dark:border-blue-800 hover:shadow-2xl transition-shadow duration-300']) }}>
    <div class="p-6">
        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300">{{ $title }}</h3>
        <div class="mt-4 space-y-3">
            @foreach ($stats as $stat)
                <div class="flex justify-between items-center p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors duration-200">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $stat['label'] }}</span>
                    <span class="text-lg font-bold {{ $stat['colorClass'] ?? 'text-gray-900 dark:text-gray-100' }}">
                        {{ $stat['value'] }}
                    </span>
                </div>
            @endforeach
        </div>
        @isset($viewMoreUrl)
            <a href="{{ $viewMoreUrl }}" class="mt-4 inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                {{ $viewMoreLabel }}
                <i class="fas fa-arrow-right ml-1 text-xs"></i>
            </a>
        @endisset
    </div>
</div> 