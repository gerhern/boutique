<?php

namespace Tests\Feature;

use App\traits\SetTestingData;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase, SetTestingData;


    //Testing restrictions
    public function test_categories_with_products_cannot_be_deleted(): void
    {

        $unrelatedCategory = $this->createCategory();
        [$relatedCategory,] = $this->createProductWithCategory();

        $this->assertDatabaseHas('categories', ['id' => $unrelatedCategory->id]);

        $unrelatedCategory->delete();

        $this->assertDatabaseMissing('categories', ['id' => $unrelatedCategory->id]);

        $this->assertDatabaseHas('categories', ['id' => $relatedCategory->id]);

        try {
            $relatedCategory->delete();
            $this->fail('QueryException expected..');
        } catch (QueryException $e) {
            $this->assertStringContainsString('23000', $e->getMessage());
        }
        $this->assertDatabaseHas('categories', ['id' => $relatedCategory->id]);
    }

    //Testing views

    public function test_categories_index_can_be_rendered(): void
    {
        $admin = $this->createAdmin();
        $categories = $this->createCategories(10);

        $this->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertViewIs('admin.categories.index')
            ->assertSee($categories[0]->name)
            ->assertSee($categories[1]->id)
            ->assertSee($categories[2]->name)
            ->assertSee($categories[5]->name)
            ->assertSee($categories[7]->name)
            ->assertSee($categories[9]->name);
    }

    //Testing CRUD methods

    public function test_index_has_categories_data(): void
    {
        $admin = $this->createAdmin();
        $this->createCategories(10);

        $response = $this->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertStatus(200);

        $response->assertViewHas('categories');
        $categories = $response->viewData('categories');
        $this->assertCount(10, $categories);
    }

    public static function categoryRequestProvider(): array
    {
        return [
            'required_name' => ['', 'name'],
            'unique_name' => ['Hats', 'name'],
            'string_name' => [['test'],'name'],
            'max_name'   => [str_repeat('a', 256), 'name']
        ];
    }

    #[DataProvider('categoryRequestProvider')]
    public function test_store_category_validations($invalidData, $field): void
    {
        $admin = $this->createAdmin();
        $this->createCategory(['name' => 'Hats']);

        $badPayload = [
            'name' => $invalidData
        ];

        $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->post(route('admin.categories.store'), $badPayload)
            ->assertRedirectBackWithErrors([$field]);
    }

    public function test_new_categories_can_be_stored(): void {
        $admin = $this->createAdmin();
        $payload = ['name' => 'Shoes'];

        $this->assertDatabaseEmpty('categories');
        $this->actingAs($admin)
            ->post(route('admin.categories.store'), $payload)
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas(['success' => 'Category created successfully']);
        $this->assertDatabaseCount('categories', 1);


    }

    #[DataProvider('categoryRequestProvider')]
    public function test_update_category_validations($invalidData, $field): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory(['name' => 'Hats']);

        $badPayload = [
            'name' => $invalidData
        ];

        $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->put(route('admin.categories.update', $category), $badPayload)
            ->assertRedirectBackWithErrors([$field]);
    }

    public function test_categories_can_be_updated(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory(['name' => 'Glov']);
        $payload = [
            'name' => 'Gloves'
        ];

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), $payload)
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success', 'Category updated successfully');

        $this->assertDatabaseMissing('categories', ['name' => 'Glov', 'id' => $category->id]);
        $this->assertDatabaseHas('categories', ['name' => 'Gloves', 'id' => $category->id]);
    }


    public function test_delete_category_on_related_categories_cannot_be_deleted(): void
    {
        $admin = $this->createAdmin();
        [$relatedCategory,] = $this->createProductWithCategory();

        $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $relatedCategory))
            ->assertRedirectBackWithErrors(['category']);
        $this->assertDatabaseHas('categories', ['id' => $relatedCategory->id]);



    }

    public function test_delete_category_on_related_categories_can_be_deleted(): void {
        $admin = $this->createAdmin();
        $unrelatedCategory = $this->createCategory();

        $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $unrelatedCategory))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success', 'Category deleted successfully');

        $this->assertDatabaseMissing('categories', ['id' => $unrelatedCategory->id]);
    }
}
