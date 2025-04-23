@props(['name', 'class' => ''])

<i {{ $attributes->merge(['class' => "fas fa-{$name} {$class}"]) }}></i> 