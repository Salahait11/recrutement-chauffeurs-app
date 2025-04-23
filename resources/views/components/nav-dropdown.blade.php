@props([
    'active' => false, // Boolean indiquant si ce groupe est actif
    'align' => 'left', // 'left' ou 'right' pour l'origine du dropdown
    'width' => '48', // Largeur du dropdown (correspond à w-48)
    'contentClasses' => 'py-1 bg-white dark:bg-gray-700', // Classes pour le contenu interne
    'triggerIcon' => null, // Nom du composant icône pour le trigger
])

@php
$alignmentClasses = match ($align) {
    'left' => 'origin-top-left left-0',
    'right' => 'origin-top-right right-0',
    default => 'origin-top',
};

$widthClasses = match ($width) {
    '48' => 'w-48',
    // Ajoutez d'autres largeurs si nécessaire
    default => 'w-48',
};

$triggerClasses = ($active ?? false)
            ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100 focus:border-indigo-700'
            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700';
@endphp

<div class="relative h-full" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false" @mouseenter="open = true" @mouseleave="open = false">
    {{-- Trigger Button --}}
    <button @click="open = !open"
            class="inline-flex items-center justify-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out h-full {{ $triggerClasses }}">

        {{-- Slot pour le contenu du trigger (peut inclure icône + texte) --}}
        @if(isset($trigger))
            {{ $trigger }}
        @else
            {{ $slot }}
        @endif

        {{-- Dropdown Arrow --}}
        <svg class="ms-1 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-0 rounded-md shadow-lg {{ $alignmentClasses }} {{ $widthClasses }}"
         style="display: none;"
         @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div> 