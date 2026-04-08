@extends('layouts.admin')

@section('title', 'Crear Rifa: ' . $product->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 pb-12">
    {{-- Breadcrumbs / Volver --}}
    <nav class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-xs text-content-disabled hover:text-accent flex items-center gap-1 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
            Back to products
        </a>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Columna Izquierda: Información del Producto (Resumen) --}}
        <div class="w-full lg:w-1/3">
            <div class="bg-bg-surface border border-border-subtle rounded-xl overflow-hidden sticky top-6">
                <div class="aspect-square bg-bg-elevated">
                    <img src="{{ asset('storage/' . ($product->primaryImage?->path ?? 'defaults/no-image.jpeg')) }}"
                         class="w-full h-full object-cover"
                         onerror="this.src='{{ asset('storage/defaults/no-image.jpeg') }}'">
                </div>
                <div class="p-5">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-accent">Selected product</span>
                    <h2 class="text-lg font-bold text-content-primary mt-1">{{ $product->name }}</h2>
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-border-subtle">
                        <span class="text-xs text-content-disabled">Original Price</span>
                        <span class="text-sm font-semibold text-content-primary">${{ number_format($product->price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Formulario de la Rifa --}}
        <div class="w-full lg:w-2/3">
            <form action="{{ route('admin.raffles.store') }}" method="POST" class="space-y-6">
                @csrf
                {{-- Input oculto para vincular el producto --}}
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="bg-bg-surface border border-border-subtle rounded-xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-content-secondary mb-6 border-b border-border-subtle pb-2">
                        Raffle Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Precio por Ticket --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-content-primary uppercase">Ticket price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-content-disabled text-sm">$</span>
                                <input type="number" name="ticket_price" step="0.01" required
                                    class="w-full pl-8 pr-4 py-2.5 bg-bg-elevated border border-border-subtle rounded-lg focus:ring-1 focus:ring-accent outline-none text-sm"
                                    placeholder="0.00" value="{{ old('ticket_price', ($product->price / 10)) }}">
                            </div>
                            <p class="text-[10px] text-content-disabled italic">Suggest: 1/10 o 1/20 of total amount.</p>
                        </div>

                        {{-- Capacidad Total --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-content-primary uppercase">Total Tickets</label>
                            <input type="number" name="max_participants" required
                                class="w-full px-4 py-2.5 bg-bg-elevated border border-border-subtle rounded-lg focus:ring-1 focus:ring-accent outline-none text-sm"
                                placeholder="Ex: 50" value="{{ old('max_participants', 10) }}">
                        </div>

                        {{-- Fecha Fin --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-content-primary uppercase">End date (aprox)</label>
                            <input type="datetime-local" name="closes_at" required
                                class="w-full px-4 py-2.5 bg-bg-elevated border border-border-subtle rounded-lg focus:ring-1 focus:ring-accent outline-none text-sm"
                                value="{{ old('closes_at', now()->addWeeks(2)->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-content-disabled hover:text-content-primary transition-colors">
                        Cancel
                    </a>
                    <x-ui.button type="submit" variant="primary" class="px-10 py-3 shadow-lg shadow-accent/20">
                        Publish raffle
                    </x-ui.button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
