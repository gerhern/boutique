<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['product_id', 'path'])]
class ProductImage extends Model
{
    /** @use HasFactory<\Database\Factories\ProductImageFactory> */
    use HasFactory;
}
