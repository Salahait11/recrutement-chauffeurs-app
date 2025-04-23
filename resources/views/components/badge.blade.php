@props([
    'color' => 'gray', // Ex: 'gray', 'blue', 'green', 'red', 'yellow', 'orange', 'purple', 'indigo'
    'text' => null, // Optionnel, peut utiliser le slot
])

@php
    $colorClasses = match ($color) {
        'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'green' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100', // Dark mode ajusté
        'red' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100', // Dark mode ajusté
        'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'orange' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'indigo' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300', // 'gray' ou défaut
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-block px-2 py-0.5 text-xs font-medium rounded-full " . $colorClasses]) }}>
    {{ $text ?? $slot }}
</span> 