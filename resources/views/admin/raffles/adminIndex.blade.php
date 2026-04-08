@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6">
    {{-- Header Responsivo --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-xl font-semibold text-content-primary">Active Raffles</h1>
            <p class="text-xs text-content-disabled mt-1">Manage tickets and winners.</p>
        </div>
    </div>

    {{-- Contenedor de Tabla/Cards --}}
    <div class="bg-bg-surface border border-border-subtle rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            {{-- El thead se oculta en móviles (hidden) y se muestra en tablets (md:table-header-group) --}}
            <thead class="hidden md:table-header-group bg-bg-elevated/50 border-b border-border-subtle">
                <tr>
                    <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled">Product</th>
                    <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled">Ticket Price</th>
                    <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled">Progress</th>
                    <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-border-subtle">
                @foreach($raffles as $raffle)
                    {{-- Cada <tr> se comporta como bloque en móvil (flex-col) y fila en escritorio (md:table-row) --}}
                    <tr class="flex flex-col md:table-row group hover:bg-bg-elevated/30 transition-colors p-4 md:p-0">

                        {{-- Columna Producto --}}
                        <td class="px-0 md:px-6 py-2 md:py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 md:w-10 md:h-10 shrink-0 rounded border border-border-subtle overflow-hidden">
                                    <img src="{{ asset('storage/' . $raffle->product->primaryImage?->path) }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.src='{{ asset('storage/defaults/no-image.jpeg') }}';">
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <a
                                    {{-- href="{{ route('admin.products.edit', $raffle->product) }}" --}}
                                     class="text-sm font-medium text-accent hover:underline truncate">
                                        {{ $raffle->product->name }}
                                    </a>
                                    <span class="text-[10px] text-content-disabled">ID: #{{ $raffle->id }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Columna Precio --}}
                        <td class="px-0 md:px-6 py-2 md:py-4 flex justify-between md:table-cell">
                            <span class="md:hidden text-[10px] uppercase font-bold text-content-disabled">Price</span>
                            <span class="text-sm font-semibold text-content-primary">
                                ${{ number_format($raffle->ticket_price, 2) }}
                            </span>
                        </td>

                        {{-- Columna Progreso --}}
                        <td class="px-0 md:px-6 py-2 md:py-4 flex flex-col md:table-cell">
                            <span class="md:hidden text-[10px] uppercase font-bold text-content-disabled mb-1 text-left">Tickets Sold</span>
                            <div class="w-full md:w-48">
                                <div class="flex justify-between mb-1">
                                    <span class="text-[10px] font-medium text-content-secondary">
                                        {{ $raffle->tickets_sold ?? 0 }} / {{ $raffle->max_participants }}
                                    </span>
                                    <span class="text-[10px] font-bold text-accent">
                                        @php $perc = $raffle->max_participants > 0 ? ($raffle->tickets_sold / $raffle->max_participants) * 100 : 0; @endphp
                                        {{ round($perc) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-bg-elevated rounded-full h-1.5">
                                    <div class="bg-accent h-1.5 rounded-full" style="width: {{ $perc }}%"></div>
                                </div>
                            </div>
                        </td>

                        {{-- Acciones --}}
                        <td class="px-0 md:px-6 py-4 md:py-4 text-right flex justify-start md:justify-end gap-2 border-t md:border-t-0 mt-2 md:mt-0 pt-4 md:pt-4">
                            <a
                            href="{{ route('admin.raffles.show', $raffle) }}"
                               class="flex-1 md:flex-none text-center px-4 py-2 md:py-1.5 text-[10px] font-bold uppercase tracking-wider border border-border-subtle rounded hover:bg-bg-elevated transition-all text-content-primary">
                                Details
                            </a>

                            <form
                            {{-- action="{{ route('admin.raffles.destroy', $raffle) }}" --}}
                             method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 md:p-1.5 text-content-secondary hover:text-state-danger transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
