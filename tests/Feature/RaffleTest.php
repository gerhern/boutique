<?php

namespace Tests\Feature;

use App\enums\Role;
use App\Models\Product;
use App\Models\Raffle;
use App\Models\RaffleEntry;
use App\Models\User;
use App\traits\SetTestingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RaffleTest extends TestCase
{
    use RefreshDatabase, SetTestingData;
    public function test_admin_cant_enter_to_raffle(): void {

        $admin = $this->createUser(Role::Admin);
        $raffle = $this->createRaffle();

        $this->actingAs($admin)
            ->post(route('raffle.entry',[$raffle]),[])
            ->assertRedirectBackWithErrors(['error' => 'Admin can\'t enter to a raffle.']);

        $this->assertDatabaseMissing('raffle_entries',['raffle_id' => $raffle->id, 'user_id' => $admin->id]);
    }

    public function test_user_can_enter_to_raffle(): void {

        $user = $this->createUser();
        $raffle = $this->createRaffle();

        $this->actingAs($user)
            ->post(route('raffle.entry', [$raffle]), [])
            ->assertRedirect(route('raffle.users', [$raffle]))
            ->assertSessionHas('success', 'Raffle entry registered successfully.');

        $this->assertDatabaseHas('raffle_entries', ['raffle_id' => $raffle->id, 'user_id' => $user->id]);
    }

    public function test_entry_increased_on_existing_enty() : void {
        $user = $this->createUser();
        $raffle = $this->createRaffle();
        $lastEntry = RaffleEntry::factory()->create(['raffle_id' => $raffle->id, 'user_id' => $user->id, 'ticket_count' => 1]);

        $this->actingAs($user)
            ->post(route('raffle.entry', [$raffle]), [])
            ->assertSessionHas('success', 'Raffle entry registered successfully.');

        $this->assertDatabaseHas('raffle_entries', [
            'raffle_id' => $raffle->id,
            'user_id' => $user->id, 
            'ticket_count' => 2
        ]);
    }
}
