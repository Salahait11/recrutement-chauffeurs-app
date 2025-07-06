@props(['stats'])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Aperçu des Statistiques</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($stats as $stat)
                <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                    <div class="text-2xl font-bold {{ $stat['colorClass'] ?? 'text-gray-900 dark:text-gray-100' }}">
                        {{ $stat['value'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $stat['label'] }}
                    </div>
                    @if(isset($stat['trend']))
                        <div class="text-xs mt-1 {{ $stat['trend'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $stat['trend'] >= 0 ? '↗' : '↘' }} {{ abs($stat['trend']) }}%
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div> 