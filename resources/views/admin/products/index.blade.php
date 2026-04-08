@extends('layouts.admin')

@section('title', 'Products Directory')

@section('content')

    {{-- Header con búsqueda y botón de creación --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-xl font-semibold text-content-primary">Products</h1>
            <p class="text-xs text-content-disabled mt-1">Manage your boutique inventory and visibility.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Buscador rápido --}}
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-content-disabled">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" placeholder="Search products..." class="pl-9 pr-4 py-2 text-sm bg-bg-surface border border-border-subtle rounded-md focus:ring-2 focus:ring-accent-muted outline-none w-64">
            </div>

            <x-ui.button variant="primary" :href="route('admin.products.create')">
                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                New Product
            </x-ui.button>
        </div>
    </div>

    {{-- Alertas de Éxito/Error --}}
    @if(session('status'))
        <div class="mb-6">
            <x-ui.alert type="success" :message="session('status')" dismissible />
        </div>
    @endif

    {{-- Tabla de Productos --}}
    <div class="bg-bg-surface border border-border-subtle rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-elevated/50 border-b border-border-subtle">
                        <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled">Product</th>
                        <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled">Category</th>
                        <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled">Price</th>
                        <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled">Status</th>
                        <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle">
                    @forelse($products as $product)
                        <tr class="hover:bg-bg-elevated/30 transition-colors group">
                            {{-- Info Principal con Miniatura --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-md overflow-hidden bg-bg-elevated border border-border-subtle shrink-0">
                                        @php $primary = $product->images->where('is_primary', true)->first(); @endphp
                                        <img src="{{ asset('storage/' . ($primary->path)) }}"
                                            onerror="this.onerror=null; this.src='{{ asset('storage/defaults/no-image.jpeg') }}';"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.products.show', $product) }}" class="text-sm font-medium text-content-primary hover:text-accent transition-colors">
                                            {{ $product->name }}
                                        </a>
                                        <p class="text-[10px] text-content-disabled mt-0.5">ID: #{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Categoría --}}
                            <td class="px-5 py-4">
                                <span class="text-xs text-content-secondary">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>

                            {{-- Precio --}}
                            <td class="px-5 py-4">
                                <span class="text-sm font-medium text-content-primary">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            </td>

                            {{-- Status Badge dinámico --}}
                            <td class="px-5 py-4">
                                @php
                                    $statusMap = [
                                        'available' => ['class' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20', 'label' => 'Available'],
                                        'reserved'  => ['class' => 'bg-amber-500/10 text-amber-600 border-amber-500/20', 'label' => 'Reserved'],
                                        'sold'      => ['class' => 'bg-slate-500/10 text-slate-500 border-slate-500/20', 'label' => 'Sold'],
                                    ];
                                    $currentStatus = $statusMap[$product->status] ?? ['class' => 'bg-gray-100 text-gray-600 border-gray-200', 'label' => $product->status];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full border text-[10px] font-bold uppercase tracking-tight {{ $currentStatus['class'] }}">
                                    {{ $currentStatus['label'] }}
                                </span>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 text-content-secondary hover:text-accent hover:bg-accent-muted rounded-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.products.show', $product) }}" class="p-1.5 text-content-secondary hover:text-content-primary hover:bg-bg-elevated rounded-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-content-disabled italic text-sm">
                                No products found. Click "New Product" to start.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($products->hasPages())
            <div class="px-5 py-4 border-t border-border-subtle bg-bg-elevated/20">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@endsection
