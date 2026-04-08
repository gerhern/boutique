@extends('layouts.admin')

@section('title', 'Product Details: ' . $product->name)

@section('content')

    {{-- Breadcrumb --}}
    <nav class="text-xs text-content-disabled mb-2">
        <a href="{{ route('dashboard') }}" class="hover:text-content-secondary transition-colors">Admin</a>
        <span class="mx-1.5">/</span>
        <a href="{{ route('admin.products.index') }}" class="hover:text-content-secondary transition-colors">Products</a>
        <span class="mx-1.5">/</span>
        <span class="text-content-secondary">{{ $product->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-lg font-medium text-content-primary">{{ $product->name }}</h1>

            {{-- status badge --}}
            @php
                $statusClasses = [
                    'available' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                    'reserved'  => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                    'raffle'    => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                    'sold'      => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                ][$product->status] ?? 'bg-accent-muted text-accent border-accent-border';
            @endphp

            <span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-wider font-bold px-2.5 py-1 rounded-full border {{ $statusClasses }}">
                {{ $product->status }}
            </span>
        </div>

        <div class="flex gap-2">
            <x-ui.button variant="ghost" :href="route('admin.products.edit', $product)" class="h-9 px-3">
                <svg class="w-3.5 h-3.5 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-5 items-start">

        {{-- main column and galley --}}
        <div class="flex flex-col gap-5">

            {{-- gallery --}}
            <div class="bg-bg-surface border border-border-subtle rounded-lg p-5">
                <h2 class="text-sm font-medium text-content-primary pb-3 mb-4 border-b border-border-subtle">
                    Gallery
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- main image --}}
                    @php $primary = $product->primaryImage()->first(); @endphp
                    <div class="aspect-3/4 rounded-lg overflow-hidden border border-border-subtle bg-bg-elevated relative group">
                        <img src="{{ asset('storage/' . ($primary->path)) }}"
                                            onerror="this.onerror=null; this.src='{{ asset('storage/defaults/no-image.jpeg') }}';"
                                            class="w-full h-full object-cover"
                                            id="main-preview"/>
                    </div>

                    {{-- small image --}}
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($product->images as $image)
                                <button
                                    onclick="document.getElementById('main-preview').src = this.querySelector('img').src"
                                    class="aspect-square rounded-md overflow-hidden border border-border-subtle hover:border-accent transition-colors bg-bg-elevated">
                                    <img src="{{ asset('storage/' . ($image->path)) }}"
                                            onerror="this.onerror=null; this.src='{{ asset('storage/defaults/no-image.jpeg') }}';"
                                            class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-auto p-4 bg-bg-elevated rounded-lg border border-border-subtle">
                            <p class="text-xs text-content-disabled mb-1 italic">Description</p>
                            <p class="text-sm text-content-secondary leading-relaxed">
                                {{ $product->description ?: 'No description provided for this product.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info --}}
            <div class="bg-bg-surface border border-border-subtle rounded-lg p-5">
                <h2 class="text-sm font-medium text-content-primary pb-3 mb-4 border-b border-border-subtle">
                    Product Details
                </h2>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[11px] uppercase tracking-wide text-content-disabled font-semibold mb-1">Price</p>
                        <p class="text-2xl font-semibold text-content-primary">${{ number_format($product->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wide text-content-disabled font-semibold mb-1">Category</p>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-accent"></span>
                            <p class="text-sm text-content-primary font-medium">{{ $product->category->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- side column and actions --}}
        <div class="flex flex-col gap-5">

            <div class="bg-bg-surface border border-border-subtle rounded-lg p-5">
                <h2 class="text-sm font-medium text-content-primary pb-3 mb-4 border-b border-border-subtle">
                    Quick Actions
                </h2>

                <div class="flex flex-col gap-2">
                    <x-ui.button variant="primary" class="w-full justify-center">
                        <svg class="w-3.5 h-3.5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Mark as Reserved
                    </x-ui.button>

                    {{-- <x-ui.button variant="ghost" class="w-full justify-center text-state-danger hover:bg-state-danger/10">
                        <svg class="w-3.5 h-3.5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                        </svg>
                        Delete Product
                    </x-ui.button> --}}
                </div>
            </div>

            <div class="bg-bg-surface border border-border-subtle rounded-lg p-5">
                <h2 class="text-[11px] uppercase tracking-wide text-content-disabled font-bold mb-4">
                    Product Info
                </h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-content-secondary">Created</span>
                        <span class="text-content-primary font-medium">{{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-content-secondary">Last Update</span>
                        <span class="text-content-primary font-medium">{{ $product->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-content-secondary">Images</span>
                        <span class="text-content-primary font-medium">{{ $product->images->count() }} / 3</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
