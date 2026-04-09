{{-- resources/views/components/ui/badge.blade.php --}}
@props([
    'color' => 'gray',
])

@php
    $classes = match($color) {
        'red'  => 'badge-red',
        'green' => 'badge-green',
        'gold'  => 'bg-accent/10 text-accent border-accent/20',
        'gray'   => 'badge-gray',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-[0.15em] border transition-colors duration-300 $classes"]) }}>
    {{-- Dot indicator: bg-current asegura que el punto sea del mismo color que el texto --}}
    <span class="w-1 h-1 rounded-full bg-current mr-1.5 opacity-80"></span>
    {{ $slot }}
</span>
