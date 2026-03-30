{{-- resources/views/components/layout/footer.blade.php --}}

<footer class="border-t border-border-subtle mt-16">
    <div class="max-w-7xl mx-auto px-4 py-10">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

            {{-- Logo y descripción --}}
            <div>
                <p class="text-sm font-semibold text-accent tracking-widest uppercase mb-1">
                    {{ config('app.name') }}
                </p>
                <p class="text-xs text-content-disabled">
                    Moda selecta nueva y seminueva.
                </p>
            </div>

            {{-- Links --}}
            <nav class="flex flex-wrap gap-x-6 gap-y-2">
                <a href="{{ route('products.index') }}" class="text-xs text-content-disabled hover:text-content-secondary transition-colors">
                    Catálogo
                </a>
                <a href="{{ route('raffles.index') }}" class="text-xs text-content-disabled hover:text-content-secondary transition-colors">
                    Rifas
                </a>
                <a href="{{ route('contact') }}" class="text-xs text-content-disabled hover:text-content-secondary transition-colors">
                    Contacto
                </a>
            </nav>

        </div>

        <div class="border-t border-border-subtle mt-8 pt-6 text-center">
            <p class="text-xs text-content-disabled">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
            </p>
        </div>

    </div>
</footer>