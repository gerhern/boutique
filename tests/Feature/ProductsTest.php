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

class ProductsTest extends TestCase
{
    use RefreshDatabase, SetTestingData;

    public function test_guest_cannot_see_public_directory(): void
    {
        $this->actingAsGuest()
            ->get(route('products.index'))
            ->assertRedirect('login');
    }

    public function test_user_can_see_public_directory(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('products.index'))
            ->assertOk();
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
        $this->assertDatabaseHas('products', ['name' => 'Testing name', 'price' => 121.12]);

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

    public function test_admin_can_see_product_show_view(): void
    {
        $admin = $this->createUser(Role::Admin);
        $category = Category::factory()->create(['name' => 'Vestidos de Gala']);
        $product = Product::factory()->create([
            'name' => 'Vestido Seda Blue',
            'price' => 1500.50,
            'status' => 'available',
            'category_id' => $category->id,
        ]);
        $product->images()->createMany([
            ['path' => 'https://picsum.photos/id/237/200/300', 'is_primary' => true],
            ['path' => 'https://picsum.photos/id/421/200/300', 'is_primary' => false],
        ]);


        $response = $this->actingAs($admin)
            ->get(route('admin.products.show', $product));


        $response->assertOk();
        $response->assertViewIs('admin.products.show');
        $response->assertSee('Vestido Seda Blue');
        $response->assertSee('Vestidos de Gala');
        $response->assertSee('$1,500.50');
        $response->assertSee('available');
        $response->assertSee('https://picsum.photos/id/237/200/300');
        $response->assertSee('https://picsum.photos/id/421/200/300');
        $response->assertSee(route('admin.products.edit', $product));
    }

    public function test_edit_product_can_be_rendered(): void
    {

        $this->withoutExceptionHandling();
        $admin = $this->createUser(Role::Admin);
        $product = $this->createProduct();

        $response = $this->actingAs($admin)
            ->get(route('admin.products.edit', $product));

        $response->assertOk()
            ->assertViewIs('admin.products.edit')
            ->assertSee($product->name)
            ->assertSee($product->price)
            ->assertSee($product->primaryImage()->first()->path, false )
            ->assertSee(route('admin.products.update', $product))
            ->assertSee('name="name"', false)
            ->assertSee('name="description"', false)
            ->assertSee('name="status"', false)
            ->assertSee('name="price"', false)
            ->assertSee('type="number"', false)
            ->assertSee('name="category_id"', false)
            ->assertSee('enctype="multipart/form-data"', false);
    }

    public function test_update_product_changes_can_be_saved(): void {
        $this->withoutExceptionHandling();

        Storage::fake('public');
        $admin = $this->createUser(Role::Admin);
        $product = $this->createProduct(['status' => ProductStatus::Available]);

        $images = [];
        array_push($images, UploadedFile::fake()->image('test_image.jpg', 600, 800));
        array_push($images, UploadedFile::fake()->image('test_image2.jpg', 600, 800));
        array_push($images, UploadedFile::fake()->image('test_image3.jpg', 600, 800));

        $payload = [
            'name'          => 'new name',
            'description'   => 'new description',
            'status'        => ProductStatus::Sold->value,
            'price'         => 12.12,
            'category_id'   => $product->category_id,
            'delete_images' => $product->images()->get()->pluck('id')->toArray(),
            'images'        => $images
        ];

        $response = $this->actingAs($admin)
            ->from(route('admin.products.edit', $product))
            ->put(route('admin.products.update', $product), $payload);

        $updatedProduct = Product::find($product->id);

        $response->assertRedirect(route('admin.products.show', $updatedProduct));

        $this->assertDatabaseHas('product_images', ['product_id' => $updatedProduct->id, 'is_primary' => true]);
        $this->assertDatabaseHas('products', ['name' => $updatedProduct->name, 'price' => $updatedProduct->price]);

        $productImage = ProductImage::first();
        Storage::disk('public')->assertExists($productImage->path);
    }

    public function test_updated_request_validate_data():void {
        $this->withExceptionHandling();
         $admin = $this->createUser(Role::Admin);
         $product = $this->createProduct();
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
            ->from(route('admin.products.edit', $product))
            ->put(route('admin.products.update', $product), $payloadRequired)
            ->assertRedirectBackWithErrors(['name', 'price', 'category_id', 'images']);


        $this->actingAs($admin)
            ->from(route('admin.products.edit', $product))
            ->put(route('admin.products.update', $product), $payloadInvalidTypes)
            ->assertRedirectBackWithErrors(['price', 'images.0']);

        $this->actingAs($admin)
            ->from(route('admin.products.edit', $product))
            ->put(route('admin.products.update', $product), $payloadLimits)
            ->assertRedirectBackWithErrors(['images', 'images.0']);
    }

    public function test_admin_index_products_can_be_rendered(): void {
        $this->withoutExceptionHandling();
        $admin = $this->createUser(Role::Admin);
        $products = $this->createProducts(10);

        $response = $this->actingAs($admin)
            ->get(route('admin.products.index'));

        $response->assertOk()
            ->assertViewIs('admin.products.index')
            ->assertSee($products[0]->name)
            ->assertSee($products[1]->price)
            ->assertSee($products[2]->primaryImage()->first()->path, false )
            ->assertSee(route('admin.products.show', $products[3]));
    }
}
