<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageObserver
{
    public function deleted(ProductImage $productImage){
        if($productImage->path && Storage::disk('public')->exists($productImage->path)){
            Storage::disk('public')->delete($productImage->path);
        }
    }
}
