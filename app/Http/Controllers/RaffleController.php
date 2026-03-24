<?php

namespace App\Http\Controllers;

use App\Models\Raffle;
use App\Models\RaffleEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RaffleController extends Controller
{
    public function entry(Request $request, Raffle $raffle) {
        $user = $request->user();

        if($user->isAdmin()){
            return back()->withErrors(['error' => 'Admin can\'t enter to a raffle.']);
        }

        $lastEntry = $user->raffleEntries()
            ->where('raffle_id', '=', $raffle->id)
            ->exists();

        if($lastEntry){
            $entry = $user->raffleEntries()
            ->where('raffle_id', '=', $raffle->id)
            ->first();

            if($entry->ticket_count >= 3){
                return back()->withErrors(['error' => 'User only can buy 3 or less tickets for a raffle.']);
            }

            $entry->update([
                'ticket_count' => $entry->ticket_count++
            ]);

            return redirect(route('raffle.users', $raffle))->with('success', 'Raffle entry registered successfully.');
        }

        $entry = RaffleEntry::create([
            'raffle_id' => $raffle->id,
            'user_id'   => $user->id,
            'ticket_count' => 1,
            'payment_status' => $request->paymentStatus,
        ]);

        return redirect(route('raffle.users', $raffle))->with('success', 'Raffle entry registered successfully.');

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
