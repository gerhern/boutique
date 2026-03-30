{{-- resources/views/components/layout/nav-link.blade.php --}}

@props([
    'href'   => '#',
    'active' => false,
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'px-3 py-1.5 text-sm rounded-md transition-colors ' . ($active
            ? 'bg-bg-elevated text-content-primary'
            : 'text-content-secondary hover:text-content-primary')
    ]) }}
>
    {{ $slot }}
</a>