@props([
    'status' => 'available',
])

@php
    $config = [
        'available' => ['label' => 'Disponible', 'class' => 'badge-available', 'dot' => 'badge-dot-available'],
        'sold'      => ['label' => 'Vendido',     'class' => 'badge-sold',      'dot' => 'badge-dot-sold'],
        'low_stock' => ['label' => 'Últimas piezas', 'class' => 'badge-low-stock', 'dot' => 'badge-dot-low-stock'],
        'raffle'    => ['label' => 'En rifa',     'class' => 'badge-raffle',    'dot' => 'badge-dot-raffle'],
        'reserved'  => ['label' => 'Reservado',   'class' => 'badge-reserved',  'dot' => 'badge-dot-reserved'],
    ];

    $current = $config[$status] ?? $config['available'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium ' . $current['class']]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $current['dot'] }}"></span>
    {{ $slot->isEmpty() ? $current['label'] : $slot }}
</span>