<?php

namespace Database\Factories;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plant>
 */
class PlantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->words(2, true)).' Plant',
            'category' => fake()->randomElement(Plant::CATEGORIES),
            'unit_price' => fake()->randomFloat(2, 5, 500),
            'description' => fake()->sentence(),
            'stock_status' => fake()->randomElement(Plant::STOCK_STATUSES),
            'user_id' => User::factory(),
        ];
    }
}
