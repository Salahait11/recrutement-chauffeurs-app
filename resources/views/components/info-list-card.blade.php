@props([
    'title',
    'items', // La collection d'éléments à afficher (ex: $upcomingInterviews)
    'viewMoreUrl' => null,
    'viewMoreLabel' => 'Voir tout →',
    'emptyText' => 'Aucun élément à afficher.',
    'titleColorClass' => 'text-gray-900 dark:text-gray-100', // Pour le titre rouge des permis
    'itemView' => null // Le nom de la vue partielle à utiliser pour chaque élément
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-blue-100 dark:border-blue-800 hover:shadow-2xl transition-shadow duration-300']) }}>
    <div class="p-6">
        <h3 class="text-lg font-semibold {{ $titleColorClass }} mb-4">{{ $title }}</h3>
        <div>
            @forelse($items as $item)
                <div class="mb-3 pb-3 border-b border-blue-100 dark:border-blue-800 last:mb-0 last:pb-0 last:border-0 hover:bg-blue-50 dark:hover:bg-blue-900/30 p-2 rounded-lg transition-colors duration-200">
                    @if($itemView)
                        @include($itemView, ['item' => $item])
                    @else
                        {{ $slot }}
                    @endif
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm italic p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">{{ $emptyText }}</p>
            @endforelse
        </div>
        @isset($viewMoreUrl)
            <a href="{{ $viewMoreUrl }}" class="mt-4 inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                {{ $viewMoreLabel }}
                <i class="fas fa-arrow-right ml-1 text-xs"></i>
            </a>
        @endisset
    </div>
</div> 