<?php

namespace Database\Seeders;

use App\enums\RaffleStatus;
use App\enums\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Raffle;
use App\Models\RaffleEntry;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => Role::Admin->value
        ]);

        $users = User::factory(5)->create([
            'role' => Role::User
        ]);

        Category::factory(4)->create();

        $products = Product::factory(10)->create();

        // 3. Crear una Rifa Activa para pruebas visuales [cite: 1, 43]
        $raffle_0 = Raffle::factory()->create([
            'product_id' => $products[0]->id,
            'status' => RaffleStatus::Active,
            'ticket_price' => 50.00
        ]);

        $raffle_1 = Raffle::factory()->create([
            'product_id' => $products[1]->id,
            'status' => RaffleStatus::Closed,
            'ticket_price' => 121.00
        ]);

        $raffle_2 = Raffle::factory()->create([
            'product_id' => $products[2]->id,
            'status' => RaffleStatus::Finished,
            'ticket_price' => 1500.00
        ]);

        RaffleEntry::factory()->create([
            'raffle_id' => $raffle_0->id,
            'user_id'   => $users[0],
        ]);

        RaffleEntry::factory()->create([
            'raffle_id' => $raffle_0->id,
            'user_id'   => $users[1],
        ]);

        RaffleEntry::factory()->create([
            'raffle_id' => $raffle_0->id,
            'user_id'   => $users[3],
        ]);

        RaffleEntry::factory()->create([
            'raffle_id' => $raffle_0->id,
            'user_id'   => $users[4],
        ]);


        //raffle_2
        RaffleEntry::factory()->create([
            'raffle_id' => $raffle_1->id,
            'user_id'   => $users[2],
        ]);

        RaffleEntry::factory()->create([
            'raffle_id' => $raffle_1->id,
            'user_id'   => $users[3],
        ]);

        RaffleEntry::factory()->create([
            'raffle_id' => $raffle_1->id,
            'user_id'   => $users[4],
        ]);

        RaffleEntry::factory()->create([
            'raffle_id' => $raffle_2->id,
            'user_id'   => $users[4],
        ]);

    }
}
