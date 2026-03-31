<?php

namespace Database\Seeders;

use App\enums\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Raffle;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => Role::Admin->value
        ]);

        // 2. Categorías y Productos con Imágenes
        $categories = Category::factory(4)->create();

        $categories->each(function ($category) {
            Product::factory(5)->create(['category_id' => $category->id])
                ->each(function ($product) {
                    // Creamos la imagen primaria obligatoria para el catálogo
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => 'products/placeholder.jpg', // Asegúrate de tener este archivo
                        'is_primary' => true,
                        'sort_order' => 0
                    ]);
                });
        });

        // 3. Crear una Rifa Activa para pruebas visuales [cite: 1, 43]
        $raffleProduct = Product::factory()->create(['status' => 'available']);
        Raffle::factory()->create([
            'product_id' => $raffleProduct->id,
            'status' => 'active',
            'ticket_price' => 50.00
        ]);
    }
}
