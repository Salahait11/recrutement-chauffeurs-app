{{-- resources/views/components/button-link.blade.php --}}
@props([
    'variant' => 'outline',
    'href' => '#',
])

@php
    $baseClasses = 'inline-block px-4 py-1.5 rounded text-sm font-medium leading-normal transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800';
    $variantClasses = match ($variant) {
        'outline' => 'border border-border-auth-link hover:border-border-auth-link-hover text-text dark:border-border-auth-link-dark dark:hover:border-border-auth-link-dark-hover dark:text-text-light',
        'ghost' => 'border border-transparent hover:border-border-auth-link text-text dark:hover:border-border-auth-link-dark dark:text-text-light',
        'solid' => 'bg-blue-600 text-white hover:bg-blue-700 border border-transparent', // Assurez-vous que les couleurs 'blue-600' etc. existent ou adaptez
        default => 'border border-border-auth-link text-text dark:border-border-auth-link-dark dark:text-text-light',
    };
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}>
    {{ $slot }}
</a> 