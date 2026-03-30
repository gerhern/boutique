<?php

namespace Tests\Feature;

use App\traits\SetTestingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase, SetTestingData;
   public function test_guest_can_see_directory(): void {
        $this->get(route('products.index'))
            ->assertOk();
   }
}
