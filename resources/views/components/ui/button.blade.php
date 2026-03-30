@props([
    'variant' => 'primary',
    'size'    => 'md',
    'type'    => 'button',
    'disabled' => false,
    'href'    => null,
])

@php
    $base = 'inline-flex items-center gap-1.5 font-medium rounded-md border transition-all duration-150 active:scale-[0.97] focus:outline-none';

    $variants = [
        'primary'   => 'bg-accent text-[#0A0A0A] border-transparent hover:bg-accent-dark',
        'secondary' => 'bg-transparent text-content-primary border-border-mid hover:bg-bg-elevated hover:border-accent-border',
        'ghost'     => 'bg-transparent text-content-secondary border-transparent hover:bg-bg-elevated hover:text-content-primary',
        'danger'    => 'bg-[rgba(239,68,68,0.10)] text-state-danger border-[rgba(239,68,68,0.25)] hover:bg-[rgba(239,68,68,0.18)]',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $disabledClasses = $disabled ? 'opacity-35 cursor-not-allowed pointer-events-none' : '';

    $classes = implode(' ', [
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size]       ?? $sizes['md'],
        $disabledClasses,
    ]);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </button>
@endif