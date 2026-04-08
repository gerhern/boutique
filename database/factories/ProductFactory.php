<?php

namespace Database\Factories;

use App\enums\ProductCondition;
use App\enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Faker\Provider\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => $this->faker->words(4, true),
            'description' => $this->faker->paragraph(1),
            'condition' => $this->faker->randomElement(ProductCondition::all()),
            'status' => $this->faker->randomElement(ProductStatus::all()),
            'price'         => $this->faker->randomFloat(2,1,1000),
            'category_id'   => Category::factory()->create(),

        ];
    }

    public function configure()
{
    return $this->afterCreating(function (Product $product) {
        $count = rand(1, 3);

        for ($i = 0; $i < $count; $i++) {
            $imageId = rand(1, 1000);
            ProductImage::factory()->create([
                'product_id' => $product->id,
                'is_primary' => $i === 0,
                'sort_order' => $i,
                'path'       => "products/{$this->faker->uuid()}.jpeg",
            ]);
        }
    });
}

    // public function configure()
    // {
    //     return $this->afterCreating(function (Product $product) {
    //         // Generamos un ID aleatorio entre 1 y 1000 para Picsum
    //         $imageId = rand(1, 1000);

    //         ProductImage::create([
    //             'product_id' => $product->id,
    //             // Usamos una proporción 3:4 que es común en catálogos textiles
    //             'path'       => "https://picsum.photos/id/{$imageId}/600/800",
    //             'is_primary' => true,
    //             'sort_order' => 0,
    //         ]);
    //     });
    // }
}
