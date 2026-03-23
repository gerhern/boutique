<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Raffle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RaffleTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_cant_enter_to_raffle(): void {

        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();
        $raffle = Raffle::factory()->create(['product_id' => $product->id]);

        $this->actingAs($admin)
            ->post(route('raffle.entry'),['raffle_id' => $raffle->id])
            ->assertRedirectBackWithErrors('Admin cant enter to a riffle');

        $this->assertDatabaseMissing('raffle_entries',['raffle_id' => $raffle->id, 'user_id' => $admin->id]);
    }
}
