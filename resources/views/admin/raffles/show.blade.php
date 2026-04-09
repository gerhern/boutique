@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 pb-12">

    {{-- Header & Status Control --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2 text-[10px] font-bold uppercase tracking-widest text-content-disabled">
                <a href="{{ route('admin.raffles.index') }}" class="hover:text-accent transition-colors">Raffles</a>
                <span>/</span>
                <span class="text-accent">Draw Management</span>
            </div>
            <h1 class="text-3xl font-extrabold text-content-primary tracking-tight">
                {{ $raffle->product->name }}
            </h1>
        </div>

        {{-- Quick Status Selector --}}
        <div class="flex items-center gap-3 bg-bg-surface border border-border-subtle p-2 rounded-xl shadow-sm">
            <form
            {{-- action="{{ route('admin.raffles.update-status', $raffle) }}" --}}
             method="POST" class="flex items-center gap-2">
                @csrf @method('PATCH')
                <select name="status" onchange="this.form.submit()"
                    class="text-[10px] font-bold uppercase tracking-wider bg-transparent border-none focus:ring-0 cursor-pointer {{ $raffle->status === 'active' ? 'text-state-success' : 'text-state-danger' }}">
                    <option value="active" {{ $raffle->status === 'active' ? 'selected' : '' }}>● Active (Open)</option>
                    <option value="closed" {{ $raffle->status === 'closed' ? 'selected' : '' }}>● Paused (Closed)</option>
                    <option value="finished" {{ $raffle->status === 'finished' ? 'selected' : '' }} disabled>● Finished</option>
                </select>
            </form>
            <div class="h-4 w-px bg-border-subtle mx-1"></div>
            <x-ui.button variant="secondary" class="!py-1.5 !px-3 text-[10px] uppercase font-bold tracking-tighter" href="{{ route('admin.raffles.edit', $raffle) }}">
                Edit
            </x-ui.button>
        </div>
    </div>

    {{-- Stats Grid --}}
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Tickets Sold Progress --}}
        <div class="bg-bg-surface border border-border-subtle rounded-2xl p-6 shadow-sm">
            <p class="text-[10px] font-bold text-content-disabled uppercase tracking-widest mb-1">Tickets Sold</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-content-primary">{{ $raffle->total_tickets_sold ?? 0 }}</span>
                <span class="text-sm font-medium text-content-disabled">/ {{ $raffle->max_participants }}</span>
            </div>
            <div class="mt-4 w-full bg-bg-elevated rounded-full h-1.5 overflow-hidden">
                @php $perc = ($raffle->total_tickets_sold / $raffle->max_participants) * 100; @endphp
                <div class="bg-accent h-full transition-all duration-700" style="width: {{ $perc }}%"></div>
            </div>
        </div>

        {{-- Accumulated Revenue (NEW) --}}
        <div class="bg-bg-surface border border-border-subtle rounded-2xl p-6 shadow-sm border-l-state-success/30">
            <p class="text-[10px] font-bold text-content-disabled uppercase tracking-widest mb-1">Accumulated Sales</p>
            <div class="flex flex-col">
                <span class="text-3xl font-black text-state-success leading-none mt-1">
                    ${{ number_format(($raffle->total_tickets_sold ?? 0) * $raffle->ticket_price, 2) }}
                </span>
                <span class="text-[10px] font-medium text-content-disabled mt-2">
                    Unit Price: ${{ number_format($raffle->ticket_price, 2) }}
                </span>
            </div>
        </div>

        {{-- Closing Date --}}
        <div class="bg-bg-surface border border-border-subtle rounded-2xl p-6 shadow-sm">
            <p class="text-[10px] font-bold text-content-disabled uppercase tracking-widest mb-1">Closing Date</p>
            <span class="text-lg font-bold text-content-primary block">{{ $raffle->closes_at->format('M d, Y') }}</span>
            <span class="text-[10px] font-medium {{ $raffle->closes_at->isPast() ? 'text-state-danger' : 'text-state-success' }}">
                {{ $raffle->closes_at->diffForHumans() }}
            </span>
        </div>

        {{-- Winner Status --}}
        <div class="bg-bg-surface border border-border-subtle rounded-2xl p-6 shadow-sm flex flex-col justify-center">
             <p class="text-[10px] font-bold text-content-disabled uppercase tracking-widest mb-1">Winner</p>
             @if($raffle->winner)
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-state-success shadow-[0_0_8px_rgba(var(--color-success),0.5)]"></span>
                    <span class="text-sm font-bold text-content-primary uppercase truncate">{{ $raffle->winner->name }}</span>
                </div>
             @else
                <span class="text-sm font-medium text-content-disabled italic">Awaiting Draw</span>
             @endif
        </div>
    </div>

    {{-- SECTION: Winner Declaration (Dark Mode Friendly) --}}
    @if(!$raffle->winner_id)
        <div class="relative group mb-10">
            <div class="absolute -inset-1 bg-gradient-to-r from-accent/20 to-accent/5 rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
            <div class="relative bg-bg-surface border border-border-subtle rounded-3xl p-8 md:p-12 overflow-hidden">
                {{-- Background Decoration --}}
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-accent/5 rounded-full blur-3xl"></div>

                <div class="max-w-xl mx-auto text-center relative z-10">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-bg-elevated border border-border-subtle rounded-2xl mb-6 shadow-inner">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-content-primary uppercase tracking-tight">Declare Official Winner</h2>
                    <p class="text-sm text-content-disabled mt-2 mb-8">Select the participant who won the external draw to finalize this raffle.</p>

                    <form
                    {{-- action="{{ route('admin.raffles.set-winner', $raffle) }}" --}}
                     method="POST" class="space-y-4 text-left">
                        @csrf @method('PATCH')

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase text-content-disabled tracking-widest ml-1">Select Participant</label>
                            <div class="relative">
                                <select name="winner_id" required
                                    class="w-full pl-4 pr-10 py-4 bg-bg-elevated border border-border-subtle rounded-2xl focus:border-accent focus:ring-0 outline-none text-sm font-bold appearance-none transition-all">
                                    <option value="">Choose a name...</option>
                                    @foreach($raffle->entries->unique('user_id') as $entry)
                                        <option value="{{ $entry->user_id }}">
                                            {{ $entry->user->name }} — {{ $entry->user->email }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-content-disabled">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            onclick="return confirm('Confirm this winner? This will mark the raffle as FINISHED.')"
                            class="w-full py-4 bg-accent hover:bg-accent-hover text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-accent/20 transition-all active:scale-[0.98] mt-2">
                            Publish Official Result
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Participants Table --}}
    <div class="bg-bg-surface border border-border-subtle rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-border-subtle flex justify-between items-center bg-bg-elevated/20">
            <h3 class="text-[11px] font-black text-content-secondary uppercase tracking-widest">Raffle Participants</h3>
            <span class="text-[10px] font-bold px-2 py-1 bg-bg-elevated border border-border-subtle rounded text-content-disabled uppercase">
                {{ $raffle->entries->count() }} Transactions
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-bold text-content-disabled uppercase tracking-wider border-b border-border-subtle bg-bg-elevated/10">
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Tickets</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4 text-right">Purchase Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle">
                    @forelse($raffle->entries as $entry)
                        <tr class="hover:bg-bg-elevated/10 transition-colors {{ $raffle->winner_id == $entry->user_id ? 'bg-state-success/5' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-content-primary flex items-center gap-2">
                                        {{ $entry->user->name }}
                                        @if($raffle->winner_id == $entry->user_id)
                                            <span class="text-[8px] bg-state-success text-white px-1.5 py-0.5 rounded-full uppercase font-black">Winner</span>
                                        @endif
                                    </span>
                                    <span class="text-[10px] text-content-disabled">{{ $entry->user->email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono font-bold text-content-secondary">x{{ $entry->ticket_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-content-primary">${{ number_format($entry->ticket_count * $raffle->ticket_price, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right text-[10px] font-medium text-content-disabled">
                                {{ $entry->created_at->format('M d, Y — H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-xs text-content-disabled italic">No participations registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
