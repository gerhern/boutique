{{-- resources/views/components/ui/stat-card.blade.php --}}

@props([
    'label'  => '',
    'value'  => 0,
    'detail' => null,
    'color'  => 'accent', // accent | success | danger | warning
])

@php
    $colorMap = [
        'accent'  => ['icon_bg' => 'bg-accent-muted',                        'icon_stroke' => 'text-accent',        'value' => 'text-accent'],
        'success' => ['icon_bg' => 'bg-[rgba(34,197,94,0.10)]',               'icon_stroke' => 'text-state-success', 'value' => 'text-state-success'],
        'danger'  => ['icon_bg' => 'bg-[rgba(239,68,68,0.10)]',               'icon_stroke' => 'text-state-danger',  'value' => 'text-state-danger'],
        'warning' => ['icon_bg' => 'bg-[rgba(245,158,11,0.10)]',              'icon_stroke' => 'text-state-warning', 'value' => 'text-state-warning'],
    ];

    $c = $colorMap[$color] ?? $colorMap['accent'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-bg-elevated border border-border-subtle rounded-lg p-4']) }}>

    {{-- Icono --}}
    @isset($icon)
        <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3 {{ $c['icon_bg'] }}">
            <svg class="w-4 h-4 {{ $c['icon_stroke'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                {{ $icon }}
            </svg>
        </div>
    @endisset

    {{-- Label --}}
    <p class="text-[11px] text-content-disabled mb-1">{{ $label }}</p>

    {{-- Valor --}}
    <p class="text-2xl font-medium leading-none {{ $c['value'] }}">{{ $value }}</p>

    {{-- Detalle --}}
    @if ($detail)
        <p class="text-[11px] text-content-disabled mt-1.5">{{ $detail }}</p>
    @endif

</div>
