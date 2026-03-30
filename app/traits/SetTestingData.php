<?php

namespace App\traits;

use App\enums\Role;
use App\Models\Product;
use App\Models\Raffle;
use App\Models\User;

trait SetTestingData
{
    public function createUser(Role $role = Role::User ): User {
        return User::factory()->create([
            'role' => $role->value
        ]);
    }

    public function createRaffle(Product $product = null): Raffle {
        return Raffle::factory()->create([
            'product_id' => $product->id ?? Product::factory()->create()
        ]);
    }
}
