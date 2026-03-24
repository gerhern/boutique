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
}
