<?php

namespace Database\Factories;

use App\Models\Raffle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Raffle>
 */
class RaffleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(5, true),
            'ticket_price'         => $this->faker->randomFloat(2,1,1000),
        ];
    }
}
