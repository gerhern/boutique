<?php

namespace App\Http\Controllers;

use App\enums\RaffleStatus;
use App\Http\Requests\admin\RaffleStoreRequest;
use App\Models\Product;
use App\Models\Raffle;
use App\Models\RaffleEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RaffleController extends Controller
{

    public function adminIndex(Request $request){
        $raffles = Raffle::with('entries')
            ->countEntries()
            ->latest()
            ->paginate(12);

        return view('admin.raffles.adminIndex', compact('raffles'));
    }

    public function create(Request $request, Product $product){
        return view('admin.raffles.create', compact('product'));
    }

    public function store(RaffleStoreRequest $request){
        $raffle = Raffle::create([
            'product_id'        => $request->product_id,
            'ticket_price'      => $request->ticket_price,
            'max_participants'  => $request->max_participants,
            'status'            => RaffleStatus::Active,
            'closes_at'         => $request->closes_at
        ]);

        return redirect()->route('admin.raffles.show', $raffle)->with('success', 'Raffle opened successfully');
    }

    public function show(Request $request, Raffle $raffle){
        $raffle->load([
            'product.primaryImage',
            'entries.user'
        ]);

        $raffle->loadSum('entries as total_tickets_sold', 'ticket_count');
        return view('admin.raffles.show', compact('raffle'));
    }


    public function entry(Request $request, Raffle $raffle) {
        $user = $request->user();

        if($user->isAdmin()){
            return redirect(route('raffles.show', $raffle))->withErrors(['error' => 'Admin can\'t enter to a raffle.']);
        }

        $lastEntry = $user->raffleEntries()
            ->where('raffle_id', '=', $raffle->id)
            ->exists();

        if($lastEntry){
            $entry = $user->raffleEntries()
            ->where('raffle_id', '=', $raffle->id)
            ->first();

            if($entry->ticket_count >= 3){
                return redirect(route('raffles.show', $raffle))->withErrors(['error' => 'User only can buy 3 or less tickets for a raffle.']);
            }

            $entry->update([
                'ticket_count' => $entry->ticket_count++
            ]);

            return redirect(route('raffles.show', $raffle))->with('success', 'Raffle entry registered successfully.');
        }

        $entry = RaffleEntry::create([
            'raffle_id' => $raffle->id,
            'user_id'   => $user->id,
            'ticket_count' => 1,
            'payment_status' => $request->paymentStatus,
        ]);

        return redirect(route('raffles.users', $raffle))->with('success', 'Raffle entry registered successfully.');

        // $raffle = Raffle::create([
        //     'product_id'    => $request->product_id,
        //     'title'         => $request->title,
        //     'description'   => $request->description ?? null,
        //     'ticket_price'  => $request->ticket_price,
        //     'max_participants'  => $request->max_participants ?? 10,
        //     'status'            => RaffleStatus::
        // ]);
    }
}
