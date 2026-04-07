@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-lg font-medium text-content-primary">Dashboard</h1>
            <p class="text-xs text-content-disabled mt-0.5">
                Bienvenido, {{ Auth::user()->name }}
            </p>
        </div>
        <x-ui.button variant="primary" :href="route('admin.products.create')">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nuevo producto
        </x-ui.button>
    </div>

    {{-- Tarjetas de métricas --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">

        <x-ui.stat-card
            label="Total productos"
            :value="$stats['total']"
            detail="Inventario general"
            color="accent"
        >
            <x-slot:icon>
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card
            label="Disponibles"
            :value="$stats['available']"
            :detail="$stats['available_pct'] . '% del inventario'"
            color="success"
        >
            <x-slot:icon>
                <polyline points="20 6 9 17 4 12"/>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card
            label="Vendidos"
            :value="$stats['sold']"
            detail="Este mes"
            color="danger"
        >
            <x-slot:icon>
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card
            label="Rifas activas"
            :value="$stats['raffles']"
            detail="En curso ahora"
            color="accent"
        >
            <x-slot:icon>
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </x-slot:icon>
        </x-ui.stat-card>

    </div>

    {{-- Tabla de productos recientes --}}
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-medium text-content-primary">Productos recientes</h2>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-content-disabled hover:text-accent transition-colors">
            Ver todos →
        </a>
    </div>

    <div class="bg-bg-surface border border-border-subtle rounded-lg overflow-hidden">

        {{-- Encabezado tabla --}}
        <div class="grid grid-cols-[2fr_1fr_1fr_1fr_100px] gap-2 px-4 py-2.5 bg-bg-elevated border-b border-border-subtle">
            <span class="text-[10px] font-medium text-content-disabled uppercase tracking-wider">Producto</span>
            <span class="text-[10px] font-medium text-content-disabled uppercase tracking-wider">Categoría</span>
            <span class="text-[10px] font-medium text-content-disabled uppercase tracking-wider">Precio</span>
            <span class="text-[10px] font-medium text-content-disabled uppercase tracking-wider">Estado</span>
            <span class="text-[10px] font-medium text-content-disabled uppercase tracking-wider">Acciones</span>
        </div>

        {{-- Filas --}}
        @forelse ($recentProducts as $product)
            <div class="grid grid-cols-[2fr_1fr_1fr_1fr_100px] gap-2 px-4 py-3 border-b border-border-subtle items-center hover:bg-bg-elevated transition-colors last:border-b-0">

                <span class="text-sm font-medium text-content-primary truncate">
                    {{ $product->name }}
                </span>

                <span class="text-xs text-content-disabled">
                    {{ $product->category->name ?? '—' }}
                </span>

                <span class="text-sm font-medium text-accent">
                    ${{ number_format($product->price, 0) }}
                </span>

                <div>
                    <x-ui.badge :status="$product->status" />
                </div>

                <div class="flex items-center gap-1.5">
                    {{-- <a
                        href="{{ route('admin.products.edit', $product) }}"
                        class="text-[11px] px-2.5 py-1 rounded border border-border-subtle text-content-secondary hover:border-accent-border hover:text-accent transition-colors"
                    >
                        Editar
                    </a> --}}
                    <a
                        href="{{ route('admin.products.show', $product) }}"
                        target="_blank"
                        class="text-[11px] px-2.5 py-1 rounded border border-border-subtle text-content-secondary hover:border-accent-border hover:text-accent transition-colors"
                    >
                        Ver
                    </a>
                </div>

            </div>
        @empty
            <div class="py-12 text-center text-sm text-content-disabled">
                No hay productos registrados aún.
            </div>
        @endforelse

    </div>

@endsection
