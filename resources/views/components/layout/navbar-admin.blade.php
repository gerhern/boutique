{{-- resources/views/components/layout/navbar-admin.blade.php --}}

<header class="sticky top-0 z-50 bg-bg-surface border-b border-border-subtle">
    <nav class="flex items-center justify-between h-[52px] px-5">

        {{-- Logo + badge admin --}}
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-accent tracking-widest uppercase">
                {{ config('app.name') }}
            </span>
            <span class="text-[9px] font-medium px-1.5 py-0.5 rounded bg-accent-muted text-accent border border-accent-border tracking-wider uppercase">
                Admin
            </span>
        </div>

        {{-- Links de navegación principales --}}
        <div class="hidden md:flex items-center gap-1">
            <x-layout.nav-link :href="route('dashboard')" :active="request()->routeIs('admin.dashboard')">
                Dashboard
            </x-layout.nav-link>
            <x-layout.nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                Productos
            </x-layout.nav-link>
            <x-layout.nav-link :href="route('admin.raffles.index')" :active="request()->routeIs('admin.raffles.*')">
                Rifas
            </x-layout.nav-link>
        </div>

        {{-- Usuario autenticado --}}
        <div class="flex items-center gap-3">

            {{-- Notificaciones --}}
            <a href="{{ route('admin.notifications.index') }}" class="relative text-content-secondary hover:text-content-primary transition-colors" aria-label="Notificaciones">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </a>

            {{-- Avatar + nombre --}}
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-2 group hover:opacity-80 transition-opacity"
                title="Editar Perfil">

                    <span class="hidden sm:block text-xs text-content-disabled group-hover:text-accent transition-colors">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </span>

                    <div class="w-7 h-7 rounded-full bg-accent-muted border border-accent-border flex items-center justify-center text-[10px] font-semibold text-accent uppercase group-hover:bg-accent group-hover:text-white transition-all">
                        {{ substr(Auth::user()->name ?? 'A', 0, 2) }}
                    </div>
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-content-disabled hover:text-content-secondary transition-colors" aria-label="Cerrar sesión">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>

        </div>
    </nav>
</header>
