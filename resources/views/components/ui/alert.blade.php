@props([
    'type'    => 'info',
    'title'   => null,
    'message' => null,
])

@php
    $config = [
        'success' => [
            'classes' => 'bg-[rgba(34,197,94,0.10)] border-[rgba(34,197,94,0.20)] text-state-success',
            'icon'    => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        ],
        'danger' => [
            'classes' => 'bg-[rgba(239,68,68,0.10)] border-[rgba(239,68,68,0.20)] text-state-danger',
            'icon'    => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        ],
        'warning' => [
            'classes' => 'bg-[rgba(245,158,11,0.10)] border-[rgba(245,158,11,0.20)] text-state-warning',
            'icon'    => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        ],
        'info' => [
            'classes' => 'bg-[rgba(59,130,246,0.10)] border-[rgba(59,130,246,0.20)] text-[#3B82F6]',
            'icon'    => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
        ],
    ];

    $current = $config[$type] ?? $config['info'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 px-3.5 py-3 rounded-md border text-sm ' . $current['classes']]) }}>

    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        {!! $current['icon'] !!}
    </svg>

    <div class="flex-1 min-w-0">
        @if ($title)
            <p class="font-medium leading-snug">{{ $title }}</p>
        @endif

        @if ($message)
            <p class="opacity-85 text-xs mt-0.5 leading-relaxed">{{ $message }}</p>
        @endif

        {{ $slot }}
    </div>

</div>