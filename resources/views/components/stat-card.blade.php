@props(['title', 'stats', 'viewMoreUrl' => null, 'viewMoreLabel' => null, 'icon' => null, 'color' => 'blue'])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                @if($icon)
                    <div class="flex-shrink-0">
                        <i class="{{ $icon }} text-{{ $color }}-600 dark:text-{{ $color }}-400 text-xl"></i>
                    </div>
                @endif
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 ml-2">{{ $title }}</h3>
            </div>
            @if($viewMoreUrl)
                <a href="{{ $viewMoreUrl }}" class="text-sm text-{{ $color }}-600 dark:text-{{ $color }}-400 hover:text-{{ $color }}-800 dark:hover:text-{{ $color }}-300 font-medium">
                    {{ $viewMoreLabel }}
                </a>
            @endif
        </div>

        <div class="space-y-3">
            @foreach($stats as $stat)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $stat['label'] }}</span>
                    <span class="text-lg font-bold {{ $stat['colorClass'] ?? 'text-gray-900 dark:text-gray-100' }}">
                        {{ $stat['value'] }}
                    </span>
                </div>
            @endforeach
        </div>

        @if(isset($stats[0]['percentage']))
            <div class="mt-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Progression</span>
                    <span class="font-semibold {{ $stats[0]['percentage'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats[0]['percentage'] >= 0 ? '+' : '' }}{{ $stats[0]['percentage'] }}%
                    </span>
                </div>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-{{ $color }}-600 h-2 rounded-full" style="width: {{ min(abs($stats[0]['percentage']), 100) }}%"></div>
                </div>
            </div>
        @endif
    </div>
</div> 