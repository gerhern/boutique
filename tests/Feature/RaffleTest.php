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
    private int $max_entries;

    protected function setUp(): void
    {
        parent::setUp();
        $this->max_entries = config('config.max_raffle_entries');
    }

    public function test_admin_cannot_enter_to_raffle(): void {

        $admin = $this->createUser(Role::Admin);
        $raffle = $this->createRaffle();

        $this->actingAs($admin)
            ->post(route('raffles.entry',[$raffle]),[])
            ->assertRedirect(route('raffles.show', [$raffle]))
            ->assertSessionHasErrors(['error' => 'Admin can\'t enter to a raffle.']);

        $this->assertDatabaseMissing('raffle_entries',['raffle_id' => $raffle->id, 'user_id' => $admin->id]);
    }

    public function test_user_can_enter_to_raffle(): void {

        $user = $this->createUser();
        $raffle = $this->createRaffle();

        $this->actingAs($user)
            ->post(route('raffles.entry', [$raffle]), [])
            ->assertRedirect(route('raffles.users', [$raffle]))
            ->assertSessionHas('success', 'Raffle entry registered successfully.');

        $this->assertDatabaseHas('raffle_entries', ['raffle_id' => $raffle->id, 'user_id' => $user->id]);
    }

    public function test_entry_increased_on_existing_entry() : void {
        $user = $this->createUser();
        $raffle = $this->createRaffle();
        RaffleEntry::factory()->create(['raffle_id' => $raffle->id, 'user_id' => $user->id, 'ticket_count' => 1]);

        $this->actingAs($user)
            ->post(route('raffles.entry', [$raffle]), [])
            ->assertSessionHas('success', 'Raffle entry registered successfully.');

        $this->assertDatabaseHas('raffle_entries', [
            'raffle_id' => $raffle->id,
            'user_id' => $user->id, 
            'ticket_count' => 2
        ]);
    }

    public function test_user_cannot_exceed_3_entries(): void {
        $user = $this->createUser();
        $raffle = $this->createRaffle();
        RaffleEntry::factory()->create(['raffle_id' => $raffle->id, 'user_id' => $user->id, 'ticket_count' => 3]);
        

        $this->actingAs($user)
            ->post(route('raffles.entry', [$raffle]), [])
            ->assertRedirect(route('raffles.show', $raffle))
            ->assertSessionHasErrors(['error' => 'User only can buy ' . $this->max_entries . ' or less tickets for a raffle.']);

        $this->assertDatabaseHas('raffle_entries', [
            'raffle_id' => $raffle->id,
            'user_id' => $user->id, 
            'ticket_count' => $this->max_entries
        ]);

    }
}
