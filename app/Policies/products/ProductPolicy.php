<?php

namespace App\Policies\products;

use App\enums\ProductStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProductPolicy
{
   public function canBeUpdated(User $user, Product $product): bool {
    return ($user->isAdmin() && !$product->status->isRestricted());
   }
}
