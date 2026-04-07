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


}
