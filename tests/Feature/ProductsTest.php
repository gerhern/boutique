<?php

namespace Tests\Feature;

use App\enums\ProductCondition;
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
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase, SetTestingData;

    //Testing restrictions
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

    public static function productRequestProvider(): array
    {
        return [
            // Name
            'name_is_required' => [['name' => ''], 'name'],
            'name_is_too_long' => [['name' => str_repeat('a', 256)], 'name'],

            // Description
            'description_must_be_string' => [['description' => 12345], 'description'],

            // Price
            'price_is_required' => [['price' => ''], 'price'],
            'price_is_not_numeric' => [['price' => 'abc'], 'price'],
            'price_too_low' => [['price' => 0.5], 'price'],
            'price_too_high' => [['price' => 10000], 'price'],

            // Enums (Condition & Status)
            'invalid_condition' => [['condition' => 'broken'], 'condition'],
            'invalid_status'    => [['status' => 'discarded'], 'status'],

            // Category
            'category_id_required' => [['category_id' => ''], 'category_id'],
            'category_does_not_exist' => [['category_id' => 999], 'category_id'],

            // Images (Array constraints)
            'images_required' => [['images' => []], 'images'],
            'images_not_an_array' => [['images' => 'not-an-array'], 'images'],
            'images_too_many' => [['images' => [1, 2, 3, 4]], 'images'],
        ];
    }

    #[DataProvider('productRequestProvider')]
    public function test_store_product_request_reject_invalid_data($invalidInputs, $field): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        $validPayload = [
            'name'          => 'Product Test',
            'description'   => 'Valid description',
            'condition'     => ProductCondition::New->value,
            'status'        => ProductStatus::Available->value,
            'price'         => 100.00,
            'category_id'   => $category->id,
            'images'        => [
                UploadedFile::fake()->image('photo1.jpg'),
            ]
        ];

        $payload = array_merge($validPayload, $invalidInputs);
        $this->actingAs($admin)
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), $payload)
            ->assertRedirectBackWithErrors([$field]);
    }

    public function test_images_on_request_must_be_valid_(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        $payload = [
            'name'        => 'Test',
            'price'       => 50,
            'category_id' => $category->id,
            'images'      => [
                UploadedFile::fake()->create('document.pdf', 500),
                UploadedFile::fake()->image('big_photo.jpg')->size(3000),
            ]
        ];

        $this->actingAs($admin)
            ->post(route('admin.products.store'), $payload)
            ->assertSessionHasErrors([
                'images.0',
                'images.1'
            ]);
    }

    #[DataProvider('productRequestProvider')]
    public function test_updated_request_validate_data($invalidInputs, $field): void
    {
        $this->withExceptionHandling();
        $admin = $this->createAdmin();
        [$category, $product] = $this->createProductWithCategory(['status' => ProductStatus::Available]);

        $validPayload = [
            'name'          => 'Product Test',
            'description'   => 'Valid description',
            'condition'     => ProductCondition::New->value,
            'status'        => ProductStatus::Available->value,
            'price'         => 100.00,
            'category_id'   => $category->id,
            'images'        => [
                UploadedFile::fake()->image('photo1.jpg'),
            ]
        ];

        $payload = array_merge($validPayload, $invalidInputs);

        $this->actingAs($admin)
            ->from(route('admin.products.edit', $product))
            ->put(route('admin.products.update', $product), $payload)
            ->assertRedirectBackWithErrors([$field]);
    }

    public function test_invalid_status_cannot_be_edited()
    {
        $this->withoutExceptionHandling();
        $admin = $this->createAdmin();

        $product = $this->createProduct(['status' => ProductStatus::Sold]);
        $category = $this->createCategory();
        $payload = [
            'name' => 'Testing Name',
            'price' => 122.01,
            'category_id' => $category->id
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.products.update', $product), $payload);
        $response->assertSessionHasErrors('status');
    }

    public function test_image_file_is_deleted_when_model_is_deleted(): void
    {
        Storage::fake('public');
        $product = $this->createProduct();
        $image = ProductImage::factory()->create(['path' => 'products/test.jpg', 'product_id' => $product->id]);

        Storage::disk('public')->put('products/test.jpg', 'content');

        $image->delete();

        Storage::disk('public')->assertMissing('products/test.jpg');
    }

    //Testing views
    public function test_admin_create_product_view_can_be_rendered(): void
    {
        $admin = $this->createAdmin();
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

    public function test_admin_product_show_view_can_be_rendered(): void
    {
        $admin = $this->createAdmin();
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

    public function test_admin_edit_product_can_be_rendered(): void
    {

        $this->withoutExceptionHandling();
        $admin = $this->createAdmin();
        $product = $this->createProduct();

        $response = $this->actingAs($admin)
            ->get(route('admin.products.edit', $product));

        $response->assertOk()
            ->assertViewIs('admin.products.edit')
            ->assertSee($product->name)
            ->assertSee($product->price)
            ->assertSee($product->primaryImage()->first()->path, false)
            ->assertSee(route('admin.products.update', $product))
            ->assertSee('name="name"', false)
            ->assertSee('name="description"', false)
            ->assertSee('name="status"', false)
            ->assertSee('name="price"', false)
            ->assertSee('type="number"', false)
            ->assertSee('name="category_id"', false)
            ->assertSee('enctype="multipart/form-data"', false);
    }

    public function test_admin_index_product_can_be_rendered(): void
    {
        $this->withoutExceptionHandling();
        $admin = $this->createAdmin();
        $products = $this->createProducts(10);

        $response = $this->actingAs($admin)
            ->get(route('admin.products.index'));

        $response->assertOk()
            ->assertViewIs('admin.products.index')
            ->assertSee($products[0]->name)
            ->assertSee($products[1]->price)
            ->assertSee($products[2]->primaryImage()->first()->path, false)
            ->assertSee(route('admin.products.show', $products[3]));
    }

    //Testing CRUD

    public function test_product_index_retrieves_data(): void
    {
        $admin = $this->createAdmin();
        $this->createProducts(10);

        $response = $this->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertStatus(200);

        $response->assertViewHas('products');
        $products = $response->viewData('products');
        $this->assertCount(10, $products);
    }

    public function test_new_product_is_stored(): void
    {
        $this->withExceptionHandling();
        Storage::fake('public');
        $admin = $this->createAdmin();
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

    public function test_update_product_changes_can_be_saved(): void
    {
        $this->withoutExceptionHandling();

        Storage::fake('public');
        $admin = $this->createAdmin();
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
}
