{{-- resources/views/components/layout/sidebar-admin.blade.php --}}

<aside class="w-56 flex-shrink-0 border-r border-border-subtle bg-bg-surface">
    <nav class="py-4">

        {{-- Catálogo --}}
        <div class="px-4 mb-1">
            <p class="text-[9px] font-medium tracking-widest uppercase text-content-disabled mb-2">
                Catálogo
            </p>
        </div>

        <x-layout.sidebar-link
            :href="route('admin.products.index')"
            :active="request()->routeIs('admin.products.index')"
        >
            <x-slot:icon>
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </x-slot:icon>
            Todos los productos
        </x-layout.sidebar-link>

        <x-layout.sidebar-link
            :href="route('admin.products.create')"
            :active="request()->routeIs('admin.products.create')"
        >
            <x-slot:icon>
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </x-slot:icon>
            Nuevo producto
        </x-layout.sidebar-link>

        <x-layout.sidebar-link
            :href="route('admin.categories.index')"
            :active="request()->routeIs('admin.categories.*')"
        >
            <x-slot:icon>
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
            </x-slot:icon>
            Categorías
        </x-layout.sidebar-link>

        {{-- Rifas --}}
        <div class="px-4 mt-5 mb-1">
            <p class="text-[9px] font-medium tracking-widest uppercase text-content-disabled mb-2">
                Rifas
            </p>
        </div>

        <x-layout.sidebar-link
            :href="route('admin.raffles.index')"
            :active="request()->routeIs('admin.raffles.index')"
        >
            <x-slot:icon>
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </x-slot:icon>
            Rifas activas
        </x-layout.sidebar-link>

        <x-layout.sidebar-link
            :href="route('admin.raffles.history')"
            :active="request()->routeIs('admin.raffles.history')"
        >
            <x-slot:icon>
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </x-slot:icon>
            Historial
        </x-layout.sidebar-link>

        {{-- Comunicación --}}
        <div class="px-4 mt-5 mb-1">
            <p class="text-[9px] font-medium tracking-widest uppercase text-content-disabled mb-2">
                Comunicación
            </p>
        </div>

        <x-layout.sidebar-link
            :href="route('admin.notifications.index')"
            :active="request()->routeIs('admin.notifications.*')"
        >
            <x-slot:icon>
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </x-slot:icon>
            Notificaciones
        </x-layout.sidebar-link>

        <x-layout.sidebar-link
            :href="route('admin.messages.index')"
            :active="request()->routeIs('admin.messages.*')"
        >
            <x-slot:icon>
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </x-slot:icon>
            Mensajes
        </x-layout.sidebar-link>

        {{-- Sistema --}}
        <div class="px-4 mt-5 mb-1">
            <p class="text-[9px] font-medium tracking-widest uppercase text-content-disabled mb-2">
                Sistema
            </p>
        </div>

        <x-layout.sidebar-link
            :href="route('admin.settings')"
            :active="request()->routeIs('admin.settings')"
        >
            <x-slot:icon>
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.07 4.93a10 10 0 0 1 1.41 13.4M4.93 4.93A10 10 0 0 0 3.52 18.33"/>
            </x-slot:icon>
            Configuración
        </x-layout.sidebar-link>

    </nav>
</aside>