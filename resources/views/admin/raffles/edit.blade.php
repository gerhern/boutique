@extends('layouts.admin')

@section('title', 'Editar Rifa: ' . $raffle->product->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 pb-12">
    {{-- Navegación --}}
    <nav class="mb-8 flex items-center justify-between">
        <a href="{{ route('admin.raffles.show', $raffle) }}" class="group inline-flex items-center text-xs font-bold uppercase tracking-widest text-content-disabled hover:text-accent transition-colors">
            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
            Volver a Detalles
        </a>
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-content-secondary">Editando Rifa #{{ $raffle->id }}</span>
        </div>
    </nav>

    @if($raffle->entries_count > 0)
        <div class="mb-6">
            <x-ui.alert type="warning">
                <p class="text-xs font-medium">
                    <strong class="uppercase">Nota:</strong> Esta rifa ya tiene <strong>{{ $raffle->entries_count }}</strong> ventas.
                    Ciertos cambios podrían afectar la integridad de los datos de los participantes.
                </p>
            </x-ui.alert>
        </div>
    @endif

    {{-- Sección de Selección de Ganador --}}
@if($raffle->total_tickets_sold > 0 && !$raffle->winner_id)
    <div class="mb-8 bg-gradient-to-br from-accent/10 via-bg-surface to-bg-surface border-2 border-accent/30 rounded-2xl p-8 shadow-xl">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-2">
                <h3 class="text-xl font-black text-content-primary uppercase tracking-tight">¡Rifa Lista para Sorteo!</h3>
                <p class="text-sm text-content-secondary">
                    Hay <strong>{{ $raffle->total_tickets_sold }}</strong> tickets en juego. El sistema seleccionará un ganador aleatorio de forma justa.
                </p>
            </div>

            <form action="{{ route('admin.raffles.draw', $raffle) }}" method="POST" onsubmit="return confirm('¿Estás seguro de realizar el sorteo ahora? Esta acción es irreversible.')">
                @csrf
                <button type="submit" class="group relative px-8 py-4 bg-accent hover:bg-accent-hover text-white rounded-xl font-bold transition-all hover:scale-105 shadow-lg shadow-accent/30">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        REALIZAR SORTEO ALEATORIO
                    </span>
                </button>
            </form>
        </div>
    </div>
@endif

{{-- Si ya hay un ganador --}}
@if($raffle->winner_id)
    <div class="mb-8 bg-state-success/10 border-2 border-state-success/30 rounded-2xl p-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-state-success text-white rounded-full mb-4 shadow-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        </div>
        <h2 class="text-2xl font-black text-state-success uppercase italic">¡Tenemos un Ganador!</h2>
        <p class="text-content-primary font-bold text-lg mt-2">{{ $raffle->winner->name }}</p>
        <p class="text-content-disabled text-sm">{{ $raffle->winner->email }}</p>
    </div>
@endif

    <form action="{{ route('admin.raffles.update', $raffle) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Resumen del Producto (No editable por seguridad) --}}
            <div class="lg:col-span-4 order-2 lg:order-1">
                <div class="bg-bg-surface border border-border-subtle rounded-2xl overflow-hidden shadow-sm opacity-80">
                    <div class="aspect-square bg-bg-elevated relative">
                        <img src="{{ asset('storage/' . ($raffle->product->primaryImage?->path ?? 'defaults/no-image.jpeg')) }}"
                             class="w-full h-full object-cover"
                             onerror="this.src='{{ asset('storage/defaults/no-image.jpeg') }}'">
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-[10px] font-bold text-content-disabled uppercase">Producto Vinculado</p>
                        <h2 class="text-sm font-bold text-content-primary mt-1">{{ $raffle->product->name }}</h2>
                        <p class="text-[10px] text-content-disabled mt-4 italic">* El producto no puede cambiarse una vez creada la rifa.</p>
                    </div>
                </div>
            </div>

            {{-- Formulario de Edición --}}
            <div class="lg:col-span-8 order-1 lg:order-2">
                <div class="bg-bg-surface border border-border-subtle rounded-2xl p-8 shadow-sm">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-content-primary">Ajustar Parámetros</h3>
                        <p class="text-sm text-content-disabled mt-1">Modifica los límites y tiempos de la campaña.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        {{-- Precio del Ticket --}}
                        <div class="space-y-2">
                            <label for="ticket_price" class="text-[11px] font-bold text-content-secondary uppercase tracking-wider">Precio por Ticket</label>
                            <div class="relative group">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-content-disabled font-medium transition-colors group-focus-within:text-accent">$</span>
                                <input type="number" id="ticket_price" name="ticket_price" step="0.01" required
                                    class="w-full pl-10 pr-4 py-3 bg-bg-elevated border border-border-subtle rounded-xl focus:ring-2 focus:ring-accent/20 focus:border-accent outline-none text-sm transition-all @if($raffle->entries_count > 0) cursor-not-allowed opacity-75 @endif"
                                    placeholder="0.00"
                                    value="{{ old('ticket_price', $raffle->ticket_price) }}"
                                    @if($raffle->entries_count > 0) readonly @endif>
                            </div>
                            @if($raffle->entries_count > 0)
                                <p class="text-[9px] text-state-warning font-medium">No se puede cambiar el precio porque ya hay tickets vendidos.</p>
                            @endif
                        </div>

                        {{-- Máximo de Participantes --}}
                        <div class="space-y-2">
                            <label for="max_participants" class="text-[11px] font-bold text-content-secondary uppercase tracking-wider">Límite de Participantes</label>
                            <div class="relative group">
                                <input type="number" id="max_participants" name="max_participants" required
                                    min="{{ $raffle->entries_count }}"
                                    class="w-full px-4 py-3 bg-bg-elevated border border-border-subtle rounded-xl focus:ring-2 focus:ring-accent/20 focus:border-accent outline-none text-sm transition-all"
                                    placeholder="Ej: 100" value="{{ old('max_participants', $raffle->max_participants) }}">
                                <svg class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 text-content-disabled" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <p class="text-[10px] text-content-disabled">Mínimo permitido: {{ $raffle->entries_count }} (vendidos).</p>
                        </div>

                        {{-- Fecha de Cierre --}}
                        <div class="md:col-span-2 space-y-2">
                            <label for="closes_at" class="text-[11px] font-bold text-content-secondary uppercase tracking-wider">Nueva Fecha de Cierre</label>
                            <input type="datetime-local" id="closes_at" name="closes_at" required
                                class="w-full px-4 py-3 bg-bg-elevated border border-border-subtle rounded-xl focus:ring-2 focus:ring-accent/20 focus:border-accent outline-none text-sm transition-all"
                                value="{{ old('closes_at', $raffle->closes_at->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                </div>

                {{-- Acciones Finales --}}
                <div class="flex items-center justify-end gap-6 pt-6">
                    <a href="{{ route('admin.raffles.show', $raffle) }}" class="text-xs font-bold uppercase tracking-widest text-content-disabled hover:text-content-primary transition-colors">
                        Cancelar Cambios
                    </a>
                    <button type="submit" class="bg-accent hover:bg-accent-hover text-white px-10 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-accent/20 hover:shadow-accent/40 transform hover:-translate-y-0.5 transition-all">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
