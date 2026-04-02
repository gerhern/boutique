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
   public function test_guest_cannot_see_directory(): void {
        $this->actingAsGuest()
        ->get(route('products.index'))
        ->assertRedirect('login');
    }

    public function test_user_can_see_directory(): void {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('products.index'))
            ->assertOk();
    }

}
