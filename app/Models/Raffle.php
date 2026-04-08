<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'product_id',
    'ticket_price',
    'max_participants',
    'status',
    'closes_at',
    'winner_id'])]
class Raffle extends Model
{
    /** @use HasFactory<\Database\Factories\RaffleFactory> */
    use HasFactory;

    public function scopeCountEntries($query){
        return $query->withSum('entries as tickets_sold', 'ticket_count');
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function entries(){
        return $this->hasMany(RaffleEntry::class);
    }
}
