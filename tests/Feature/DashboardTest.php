<?php

namespace Tests\Feature;

use App\enums\ProductStatus;
use App\enums\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\traits\SetTestingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase, SetTestingData;

    public function test_dashboard_can_be_rendered(): void
    {
        $admin = $this->createUser(Role::Admin);
        $products = $this->createProducts(10);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertViewIs('dashboard')
            ->assertSee($products[0]->name)
            ->assertSee($products[7]->name);
    }

    public function test_admin_create_new_product_view_can_be_rendered(): void
    {
        $admin = $this->createUser(Role::Admin);
        Category::factory(5)->create();

        $this->actingAs($admin)
            ->get(route('admin.products.create'))
            ->assertSee('name="name"', false)
            ->assertSee('name="description"', false)
            ->assertSee('name="status"', false)
            ->assertSee('name="price"', false)
            ->assertSee('type="number"', false)
            ->assertSee('name="category_id"', false)
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertViewHas('categories', function ($categories) {
                return $categories->count() === 5;
            });
    }

    public function test_new_product_is_stored(): void
    {
        Storage::fake('public');
        $admin = $this->createUser(Role::Admin);
        $category = $this->createCategory();
        $images = [];
        array_push($images, UploadedFile::fake()->image('test_image.jpg', 600, 800));
        array_push($images, UploadedFile::fake()->image('test_image2.jpg', 600, 800));
        array_push($images, UploadedFile::fake()->image('test_image3.jpg', 600, 800));

        $payload = [
            'name'          => 'Testing name',
            'description'   => 'Testing description',
            'status'        => ProductStatus::Available->value,
            'price'         => 121.12,
            'category_id'   => $category->id,
            'images'        => $images
        ];

        $response = $this->actingAs($admin)
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), $payload);

        $product = Product::latest()->first();

        $response->assertRedirect(route('admin.products.show', $product));

        $this->assertDatabaseHas('product_images', ['product_id' => $product->id, 'is_primary' => true]);

        $productImage = ProductImage::first();
        Storage::disk('public')->assertExists($productImage->path);
    }

    public function test_all_store_input_validations_works(): void
    {
        $admin = $this->createUser(Role::Admin);
        $category = $this->createCategory();

        $payloadRequired = [
            'name'          => '',
            'description'   => 'Test description',
            'status'        => ProductStatus::Available->value,
            'price'         => null,
            'category_id'   => '',
            'images'        => []
        ];

        $payloadInvalidTypes = [
            'name'          => 'Dress',
            'description'   => 'Description',
            'status'        => 'STATUS',
            'price'         => 'main_text',
            'category_id'   => $category->id,
            'images'        => [
                UploadedFile::fake()->create('file.pdf', 100, 'application/pdf'),
            ]
        ];

        $payloadLimits = [
            'name'          => 'heavy product',
            'description'   => 'Test',
            'status'        => ProductStatus::Available->value,
            'price'         => 99.99,
            'category_id'   => $category->id,
            'images'        => [
                UploadedFile::fake()->image('heavy_image.jpg')->size(3000),
                UploadedFile::fake()->image('img2.jpg'),
                UploadedFile::fake()->image('img3.jpg'),
                UploadedFile::fake()->image('img4.jpg'),
            ]
        ];

        $this->actingAs($admin)
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), $payloadRequired)
            ->assertRedirectBackWithErrors(['name', 'price', 'category_id', 'images']);


        $this->actingAs($admin)
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), $payloadInvalidTypes)
            ->assertRedirectBackWithErrors(['price', 'images.0']);

        $this->actingAs($admin)
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), $payloadLimits)
            ->assertRedirectBackWithErrors(['images', 'images.0']);
    }
}
