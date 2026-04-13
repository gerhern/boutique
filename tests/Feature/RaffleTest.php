<?php

namespace Tests\Feature;

use App\enums\RaffleStatus;
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

        $admin = $this->createAdmin();
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
        $admin = $this->createAdmin();
        $raffle = $this->createRaffle();

        $this->actingAs($admin)
            ->get(route('admin.raffles.index'))
            ->assertViewIs('admin.raffles.adminIndex')
            ->assertSee($raffle->name, false)
            ->assertSee($raffle->product->name, false);
    }

    public function test_create_raffle_view_can_be_rendered(): void
    {
        $admin = $this->createAdmin();
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
        $admin = $this->createAdmin();
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

    public function test_show_raffle_can_be_rendered(): void
    {
        $admin = $this->createAdmin();
        $raffle = $this->createRaffle();

        $response = $this->actingAs($admin)
            ->get(route('admin.raffles.show', $raffle));


        $response->assertOk();
        $response->assertViewIs('admin.raffles.show');
        $response->assertSee($raffle->ticket_price);
        $response->assertSee($raffle->max_participants);
        $response->assertSee($raffle->status);
        $response->assertSee(route('admin.raffles.edit', $raffle));
    }

    public function test_edit_raffle_can_be_redered(): void
    {
        $this->withExceptionHandling();
        $admin = $this->createAdmin();
        $raffle = $this->createRaffle();

        $this->actingAs($admin)
            ->get(route('admin.raffles.edit', $raffle))
            ->assertViewIs('admin.raffles.edit')
            ->assertSee('name="max_participants"', false)
            ->assertSee('name="closes_at"', false)
            ->assertSee('name="ticket_price"', false)
            ->assertSee($raffle->product->name, false);
    }

    public function test_raffle_can_be_canceled(): void
    {
        $admin = $this->createAdmin();
        $raffle = $this->createRaffle();

        $this->actingAs($admin)
            ->delete(route('admin.raffles.destroy', $raffle))
            ->assertRedirect(route('admin.raffles.index'))
            ->assertSessionHas('success', 'Raffle canceled successfully');

        $this->assertDatabaseHas('raffles', ['status' => RaffleStatus::Canceled]);
    }

    public function test_raffle_in_inactive_status_cannot_be_canceled(): void
    {
        $admin = $this->createAdmin();
        $raffleFinished = $this->createRaffle(null, ['status' => RaffleStatus::Finished]);
        $raffleClosed = $this->createRaffle(null, ['status' => RaffleStatus::Closed]);

        $this->actingAs($admin)
            ->delete(route('admin.raffles.destroy', $raffleFinished))
            ->assertSessionHasErrors(['status' => "Raffle with status {$raffleFinished->status->value} cannot be canceled"]);

        $this->actingAs($admin)
            ->delete(route('admin.raffles.destroy', $raffleClosed))
            ->assertSessionHasErrors(['status' => "Raffle with status {$raffleClosed->status->value} cannot be canceled"]);

        $this->assertDatabaseMissing('raffles', ['status' => RaffleStatus::Canceled]);
    }
}
