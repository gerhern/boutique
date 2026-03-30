{{-- resources/views/components/layout/mobile-menu.blade.php --}}

<div
    id="mobile-menu"
    class="hidden md:hidden bg-bg-surface border-b border-border-subtle"
>
    <nav class="flex flex-col px-4 py-2">
        <a
            href="{{ route('products.index') }}"
            class="py-3 text-sm border-b border-border-subtle transition-colors
                {{ request()->routeIs('catalogue.*')
                    ? 'text-accent'
                    : 'text-content-secondary hover:text-content-primary' }}"
        >
            Catálogo
        </a>
        <a
            href="{{ route('raffles.index') }}"
            class="py-3 text-sm border-b border-border-subtle transition-colors
                {{ request()->routeIs('raffles.*')
                    ? 'text-accent'
                    : 'text-content-secondary hover:text-content-primary' }}"
        >
            Rifas
        </a>
        <a
            href="{{ route('contact') }}"
            class="py-3 text-sm transition-colors
                {{ request()->routeIs('contact')
                    ? 'text-accent'
                    : 'text-content-secondary hover:text-content-primary' }}"
        >
            Contacto
        </a>
    </nav>
</div>