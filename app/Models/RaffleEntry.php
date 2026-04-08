<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['raffle_id', 'user_id'])]
class RaffleEntry extends Model
{
    /** @use HasFactory<\Database\Factories\RaffleEntryFactory> */
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function raffle(){
        return $this->belongsTo(Raffle::class);
    }
}
