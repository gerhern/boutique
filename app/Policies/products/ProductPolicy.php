<?php

namespace App\Policies\products;

use App\enums\ProductStatus;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
   public function canBeUpdated(Product $product): bool {
    return  in_array($product->status, ProductStatus::statusRestricted());
   }
}
