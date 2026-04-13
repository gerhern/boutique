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
    public function createUser(): User
    {
        return User::factory()->create([
            'role' => Role::User
        ]);
    }

    public function createAdmin(): User
    {
        return User::factory()->create([
            'role' => Role::Admin
        ]);
    }

    public function createProduct(array $data = [])
    {
        return Product::factory()->create($data);
    }

    public function createProducts(int $qty = 2)
    {
        return Product::factory($qty)->create();
    }

    public function createRaffle(Product $product = null, array $raffleData = []): Raffle
    {

        $product_id = ['product_id' => $product->id ?? Product::factory()->create()];
        $data = array_merge($product_id, $raffleData);
        return Raffle::factory()->create($data);
    }

    //categories
    public function createCategory(array $data = []): Category
    {
        return Category::factory()->create($data);
    }

    public function createCategories(int $qty = 2): Collection
    {
        return Category::factory($qty)->create();
    }

    public function createProductWithCategory(array $productData = [],array $categoryData = []){
        $category = Category::factory()->create($categoryData);
        $product = Product::factory()->create(array_merge(['category_id' => $category->id], $productData));
        return [$category, $product];
    }

    public function createImage(string $imgName = 'testing_photo.jpg')
    {
        Storage::fake('public');
        return UploadedFile::fake()->image($imgName, 600, 800);
    }
}
