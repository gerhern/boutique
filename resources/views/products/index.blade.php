@extends('layouts.app')

@section('title', 'Catálogo Textil | Boutique')

@section('content')
    {{-- Contenedor principal con el fondo base definido en tu Guía de Estilos --}}
    <div class="bg-bg-base min-h-screen">

        {{-- Header del Catálogo --}}
        <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-b border-border-subtle">
            <h1 class="text-display text-accent mb-2">Catálogo Textil</h1>
            <p class="text-text-secondary uppercase tracking-widest text-[10px]">
                Selección exclusiva de prendas nuevas y seminuevas
            </p>
        </header>

        {{-- Sección de Grid de Productos --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            @if($products->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <p class="text-h2 text-text-disabled">No hay productos disponibles actualmente.</p>
                </div>
            @else
                                {{-- Grid usando la escala de espaciado lg (24px) de tu guía --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                                {{-- Paginación centrada --}}
                                <div class="mt-16 flex justify-center">
                                    {{ $products->links() }}
                                </div>
            @endif
        </section>
    </div>
@endsection
