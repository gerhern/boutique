{{-- resources/views/components/layout/navbar-public.blade.php --}}

<header class="sticky top-0 z-50 bg-bg-surface border-b border-border-subtle">
    <nav class="flex items-center justify-between h-[52px] px-4 max-w-7xl mx-auto">

        {{-- Logo --}}
        <a href="{{ route('products.index') }}" class="text-sm font-semibold text-accent tracking-widest uppercase">
            {{ config('app.name') }}
        </a>

        {{-- Acciones desktop + hamburguesa --}}
        <div class="flex items-center gap-3">

            {{-- Links desktop (ocultos en mobile) --}}
            <div class="hidden md:flex items-center gap-1">
                <x-layout.nav-link :href="route('products.index')" :active="request()->routeIs('catalogue.*')">
                    Catálogo
                </x-layout.nav-link>
                <x-layout.nav-link :href="route('raffles.index')" :active="request()->routeIs('raffles.*')">
                    Rifas
                </x-layout.nav-link>
                <x-layout.nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                    Contacto
                </x-layout.nav-link>
            </div>

            {{-- Icono búsqueda --}}
            <a href="{{ route('products.index') }}" class="text-content-secondary hover:text-content-primary transition-colors" aria-label="Buscar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </a>

            {{-- Botón hamburguesa (solo mobile) --}}
            <button
                id="menu-btn"
                class="md:hidden flex flex-col gap-[5px] p-1 text-content-secondary hover:text-content-primary transition-colors"
                aria-label="Abrir menú"
                aria-expanded="false"
                aria-controls="mobile-menu"
            >
                <span class="block w-[18px] h-[1.5px] bg-current rounded"></span>
                <span class="block w-[18px] h-[1.5px] bg-current rounded"></span>
                <span class="block w-[18px] h-[1.5px] bg-current rounded"></span>
            </button>

        </div>
    </nav>
</header>