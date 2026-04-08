<?php

namespace Tests\Feature;

use App\enums\Role;
use App\traits\SetTestingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase, SetTestingData;

    public function test_categories_index_can_be_rendered(): void {
        $admin = $this->createUser(Role::Admin);
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


    public function test_categories_can_be_updated(): void {
        $admin = $this->createUser(Role::Admin);
        $category = $this->createCategory();
        $payload = [
            'name' => 'Testing name'
        ];

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), $payload)
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success', 'Category updated successfully.');

        $this->assertDatabaseMissing('categories', ['name' => $category->name, 'id' => $category->id]);
        $this->assertDatabaseHas('categories', ['name' => 'Testing name', 'id' => $category->id]);
    }

    public function test_validation_category_update_is_working(): void {
        $admin = $this->createUser(Role::Admin);
        $category = $this->createCategory();

        $payloadRequired = [
            'name' => ''
        ];

        $this->actingAs($admin)
            ->from(route('admin.categories.index', $category))
            ->put(route('admin.categories.update', $category), $payloadRequired)
            ->assertRedirectBackWithErrors(['name']);
    }

    public function test_delete_category_works_on_empty_category(): void {
        $admin = $this->createUser(Role::Admin);
        $category = $this->createCategory();
        $newCategory = $this->createCategory();
        $this->createProduct(['category_id' => $category->id]);

        $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirectBackWithErrors(['category']);
        $this->assertDatabaseHas('categories', ['id' => $category->id]);


        $this->delete(route('admin.categories.destroy', $newCategory))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success', 'Category deleted successfully');

        $this->assertDatabaseMissing('categories', ['id' => $newCategory->id]);


    }
}
