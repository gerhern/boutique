@extends('layouts.admin')

@section('title', 'Detalles de la Rifa - ' . $raffle->product->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 pb-12">

    {{-- Header con Estado --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.raffles.index') }}" class="text-xs font-bold text-accent uppercase tracking-widest hover:underline">Rifas</a>
                <span class="text-content-disabled">/</span>
                <span class="text-xs font-bold text-content-disabled uppercase tracking-widest">ID #{{ $raffle->id }}</span>
            </div>
            <h1 class="text-2xl font-bold text-content-primary">{{ $raffle->product->name }}</h1>
        </div>

        <div class="flex items-center gap-3">
            @if($raffle->total_tickets_sold >= $raffle->max_participants)
                <span class="px-3 py-1 bg-state-success/10 text-state-success text-[10px] font-bold uppercase rounded-full border border-state-success/20">Agotada</span>
            @elseif(now()->isAfter($raffle->closes_at))
                <span class="px-3 py-1 bg-state-danger/10 text-state-danger text-[10px] font-bold uppercase rounded-full border border-state-danger/20">Cerrada</span>
            @else
                <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold uppercase rounded-full border border-accent/20 animate-pulse">Activa</span>
            @endif

            <x-ui.button variant="secondary" class="text-xs" href="{{ route('admin.raffles.edit', $raffle) }}">
                Editar Rifa
            </x-ui.button>
        </div>
    </div>

    {{-- Grid de Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Progreso de Ventas --}}
        <div class="md:col-span-2 bg-bg-surface border border-border-subtle rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-end mb-4">
                <div>
                    <p class="text-[10px] font-bold text-content-disabled uppercase tracking-widest">Progreso de Ventas</p>
                    <h3 class="text-3xl font-black text-content-primary mt-1">
                        {{ $raffle->total_tickets_sold ?? 0 }} <span class="text-sm font-medium text-content-disabled">/ {{ $raffle->max_participants }}</span>
                    </h3>
                </div>
                <span class="text-lg font-bold text-accent">{{ round(($raffle->total_tickets_sold / $raffle->max_participants) * 100) }}%</span>
            </div>
            <div class="w-full bg-bg-elevated rounded-full h-3">
                <div class="bg-accent h-3 rounded-full transition-all duration-1000" style="width: {{ ($raffle->total_tickets_sold / $raffle->max_participants) * 100 }}%"></div>
            </div>
        </div>

        {{-- Recaudación Estimada --}}
        <div class="bg-bg-surface border border-border-subtle rounded-2xl p-6 shadow-sm">
            <p class="text-[10px] font-bold text-content-disabled uppercase tracking-widest">Recaudación</p>
            <h3 class="text-2xl font-bold text-state-success mt-1">
                ${{ number_format(($raffle->total_tickets_sold ?? 0) * $raffle->ticket_price, 2) }}
            </h3>
            <p class="text-[10px] text-content-disabled mt-2">Precio ticket: ${{ number_format($raffle->ticket_price, 2) }}</p>
        </div>

        {{-- Tiempo Restante --}}
        <div class="bg-bg-surface border border-border-subtle rounded-2xl p-6 shadow-sm">
            <p class="text-[10px] font-bold text-content-disabled uppercase tracking-widest">Cierre</p>
            <h3 class="text-lg font-bold text-content-primary mt-1">
                {{ $raffle->closes_at->diffForHumans() }}
            </h3>
            <p class="text-[10px] text-content-disabled mt-2">{{ $raffle->closes_at->format('d M, Y H:i') }}</p>
        </div>
    </div>

    {{-- Listado de Participantes --}}
    <div class="bg-bg-surface border border-border-subtle rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-border-subtle bg-bg-elevated/30 flex justify-between items-center">
            <h3 class="text-sm font-bold text-content-primary uppercase tracking-wider">Entradas Recientes</h3>
            <span class="text-[10px] font-medium text-content-disabled">{{ $raffle->entries->count() }} transacciones realizadas</span>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-border-subtle bg-bg-elevated/10">
                    <th class="px-6 py-3 text-[10px] font-bold uppercase text-content-disabled">Usuario</th>
                    <th class="px-6 py-3 text-[10px] font-bold uppercase text-content-disabled">Tickets</th>
                    <th class="px-6 py-3 text-[10px] font-bold uppercase text-content-disabled">Total Pagado</th>
                    <th class="px-6 py-3 text-[10px] font-bold uppercase text-content-disabled">Fecha</th>
                    <th class="px-6 py-3 text-[10px] font-bold uppercase text-content-disabled text-right">Referencia</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-subtle">
                @forelse($raffle->entries as $entry)
                    <tr class="hover:bg-bg-elevated/20 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center text-accent font-bold text-xs">
                                    {{ strtoupper(substr($entry->user->name, 0, 2)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-content-primary">{{ $entry->user->name }}</span>
                                    <span class="text-[10px] text-content-disabled">{{ $entry->user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-bg-elevated border border-border-subtle rounded text-xs font-bold text-content-primary">
                                {{ $entry->ticket_count }} tickets
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-content-primary">
                            ${{ number_format($entry->ticket_count * $raffle->ticket_price, 2) }}
                        </td>
                        <td class="px-6 py-4 text-xs text-content-secondary">
                            {{ $entry->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[10px] font-mono text-content-disabled">#{{ $entry->id }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-border-subtle mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z"/></svg>
                            <p class="text-sm text-content-disabled">Aún no hay participantes en esta rifa.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
