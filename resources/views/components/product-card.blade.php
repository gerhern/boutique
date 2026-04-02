{{-- resources/views/components/product-card.blade.php --}}
@props(['product'])

<div class="group bg-bg-surface border border-border-subtle rounded-lg overflow-hidden transition-all duration-300 hover:bg-bg-elevated hover:border-accent-border">

    @php
    $imagePath = $product->primaryImage?->path ?? 'https://via.placeholder.com/600x800?text=No+Image';
    // Si no empieza con http, asumimos que es local y usamos el asset helper
    $src = str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath);
@endphp

    <div class="relative aspect-3/4 overflow-hidden bg-bg-overlay">
        <img src="{{ $src }}"
             alt="{{ $product->name }}"
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
             loading="lazy">
        {{-- <img src="{{ asset('storage/' . ($product->primaryImage?->path ?? 'products/default.jpg')) }}"
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"> --}}

        {{-- Uso del nuevo namespace para badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-2">
            <x-ui.badge :status="$product->status" />
            @if($product->raffle)
                <x-ui.badge status="raffle" />
            @endif
        </div>
    </div>

    <div class="p-4 space-y-4">
        <div>
            <h3 class="text-h3 text-text-primary line-clamp-1">{{ $product->name }}</h3>
            <p class="text-caption text-text-secondary">{{ $product->category->name }}</p>
        </div>

        <div class="flex items-center justify-between">
            <span class="text-price text-accent">${{ number_format($product->price, 0) }}</span>
            {{-- Badge pequeño para la condición --}}
            <span class="text-[10px] text-text-disabled border border-border-subtle px-2 py-0.5 rounded">
                {{ strtoupper($product->condition) }}
            </span>
        </div>

        {{-- Uso del nuevo namespace para botones --}}
        <x-ui.button
            variant="{{ $product->isAvailable() ? 'primary' : 'secondary' }}"
            href="{{ route('products.show', $product) }}"
            class="w-full justify-center"
            :disabled="!$product->isAvailable()"
        >
            {{ $product->isAvailable() ? 'Ver Detalles' : 'No Disponible' }}
        </x-ui.button>
    </div>
</div>
