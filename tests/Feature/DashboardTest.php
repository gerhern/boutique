<?php

namespace Tests\Feature;

use App\enums\Role;
use App\Models\Category;
use App\traits\SetTestingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
