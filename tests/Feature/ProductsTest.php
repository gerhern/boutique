<?php

namespace Tests\Feature;

use App\Models\Product;
use App\traits\SetTestingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase, SetTestingData;
   public function test_users_can_see_directory(): void {
        $products = Product::factory(25)->create();
        $this->get(route('products.index'))
            ->assertOk();
   }
}
