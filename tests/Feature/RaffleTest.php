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

    public function test_admin_cannot_enter_to_raffle(): void
    {

        $admin = $this->createUser(Role::Admin);
        $raffle = $this->createRaffle();

        $this->actingAs($admin)
            ->post(route('raffles.entry', [$raffle]), [])
            ->assertRedirect(route('raffles.show', [$raffle]))
            ->assertSessionHasErrors(['error' => 'Admin can\'t enter to a raffle.']);

        $this->assertDatabaseMissing('raffle_entries', ['raffle_id' => $raffle->id, 'user_id' => $admin->id]);
    }

    public function test_user_can_enter_to_raffle(): void
    {

        $user = $this->createUser();
        $raffle = $this->createRaffle();

        $this->actingAs($user)
            ->post(route('raffles.entry', [$raffle]), [])
            ->assertRedirect(route('raffles.users', [$raffle]))
            ->assertSessionHas('success', 'Raffle entry registered successfully.');

        $this->assertDatabaseHas('raffle_entries', ['raffle_id' => $raffle->id, 'user_id' => $user->id]);
    }

    public function test_entry_increased_on_existing_entry(): void
    {
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

    public function test_user_cannot_exceed_3_entries(): void
    {
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

    public function test_raffles_index_can_be_rendered(): void
    {
        $this->withExceptionHandling();
        $admin = $this->createUser(Role::Admin);
        $raffle = $this->createRaffle();

        $this->actingAs($admin)
            ->get(route('admin.raffles.index'))
            ->assertViewIs('admin.raffles.adminIndex')
            ->assertSee($raffle->name, false)
            ->assertSee($raffle->product->name, false);
    }

    public function test_create_raffle_view_can_be_rendered(): void
    {
        $admin = $this->createUser(Role::Admin);
        $product = $this->createProduct();

        $this->actingAs($admin)
            ->get(route('admin.raffles.create', $product))
            ->assertViewIs('admin.raffles.create')
            ->assertSee('name="product_id"', false)
            ->assertSee('name="max_participants"', false)
            ->assertSee('name="closes_at"', false)
            ->assertSee('name="ticket_price"', false);
    }

    public function test_new_raffle_can_be_stored(): void
    {
        $admin = $this->createUser(Role::Admin);
        $product = $this->createProduct();

        $goodPayload = [
            'product_id' => $product->id,
            'max_participants' => '50',
            'ticket_price'  => 1250.73
        ];

        $badPayload = [
            'ticket_price' => ''
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.raffles.store'), $goodPayload);
        $raffle = Raffle::latest()->first();

            $response->assertRedirect(route('admin.raffles.show', $raffle))
            ->assertSessionHas('success', 'Raffle opened successfully');

        $this->actingAs($admin)
            ->from(route('admin.raffles.create', $product))
            ->post(route('admin.raffles.store'), $badPayload)
            ->assertRedirectBackWithErrors(['ticket_price', 'product_id', 'max_participants']);
    }
}
