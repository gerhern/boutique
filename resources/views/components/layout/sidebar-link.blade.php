{{-- resources/views/components/layout/sidebar-link.blade.php --}}

@props([
    'href'   => '#',
    'active' => false,
])

<a
    href="{{ $href }}"
    class="flex items-center gap-2.5 px-4 py-2 text-xs transition-colors border-l-2
        {{ $active
            ? 'text-accent border-l-accent bg-accent-muted'
            : 'text-content-secondary border-l-transparent hover:text-content-primary hover:bg-bg-elevated' }}"
>
    {{-- Icono SVG (pasado como slot nombrado) --}}
    @isset($icon)
        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            {{ $icon }}
        </svg>
    @endisset

    {{ $slot }}
</a>