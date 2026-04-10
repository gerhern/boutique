<?php

namespace App\Models;

use App\enums\ProductStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'description',
    'condition',
    'status',
    'price',
    'category_id'])]
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $casts = [
            'status' => ProductStatus::class
        ];

    public function scopeAvailable($query){
        return $query->where('status', ProductStatus::Available);
    }

    public function isAvailable(): bool {
        return $this->status === ProductStatus::Available->value;
    }

    public function raffle(){
        return $this->hasOne(Raffle::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage(){
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }


}
