<?php

namespace App\traits;

use App\enums\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\Raffle;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait SetTestingData
{
    public function createUser(Role $role = Role::User ): User {
        return User::factory()->create([
            'role' => $role->value
        ]);
    }

    public function createProduct(array $data = []){
        return Product::factory()->create($data);
    }

    public function createProducts(int $qty = 2){
        return Product::factory($qty)->create();
    }

    public function createRaffle(Product $product = null): Raffle {
        return Raffle::factory()->create([
            'product_id' => $product->id ?? Product::factory()->create()
        ]);
    }

    public function createCategory(): Category {
        return Category::factory()->create();
    }

    public function createCategories(int $qty = 2): Collection {
        return Category::factory($qty)->create();
    }

    public function createImage(string $imgName = 'testing_photo.jpg'){
        Storage::fake('public');
        return UploadedFile::fake()->image($imgName, 600, 800);

    }
}
