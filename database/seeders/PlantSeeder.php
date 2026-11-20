<?php

namespace Database\Seeders;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PlantSeeder extends Seeder
{
    /**
     * Seed a small, predictable inventory so reports and both export formats
     * can be tested across every category and stock status.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@plants.test'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $plants = [
            ['name' => 'Peace Lily', 'category' => 'flowering', 'unit_price' => 350, 'description' => 'White blooming indoor plant.', 'stock_status' => 'in_stock'],
            ['name' => 'Snake Plant', 'category' => 'ornamental', 'unit_price' => 420, 'description' => 'Hardy upright foliage plant.', 'stock_status' => 'in_stock'],
            ['name' => 'Aloe Vera', 'category' => 'succulent', 'unit_price' => 180, 'description' => 'Medicinal succulent with thick leaves.', 'stock_status' => 'in_stock'],
            ['name' => 'Basil', 'category' => 'herb', 'unit_price' => 90, 'description' => 'Fast-growing culinary herb.', 'stock_status' => 'out_of_stock'],
            ['name' => 'Money Plant', 'category' => 'ornamental', 'unit_price' => 275, 'description' => 'Trailing vine for hanging baskets.', 'stock_status' => 'in_stock'],
            ['name' => 'Marigold', 'category' => 'flowering', 'unit_price' => 120, 'description' => 'Bright orange garden flower.', 'stock_status' => 'out_of_stock'],
            ['name' => 'Jade Plant', 'category' => 'succulent', 'unit_price' => 310, 'description' => 'Tree-like succulent for desks.', 'stock_status' => 'in_stock'],
            ['name' => 'Mint', 'category' => 'herb', 'unit_price' => 85, 'description' => 'Fragrant herb for teas and cooking.', 'stock_status' => 'in_stock'],
            ['name' => 'Orchid', 'category' => 'flowering', 'unit_price' => 890, 'description' => 'Delicate long-lasting blooms.', 'stock_status' => 'out_of_stock'],
            ['name' => 'Rubber Plant', 'category' => 'ornamental', 'unit_price' => 640, 'description' => 'Glossy-leaved statement plant.', 'stock_status' => 'in_stock'],
            ['name' => 'Cactus Mix', 'category' => 'succulent', 'unit_price' => 220, 'description' => 'Assorted small desert cacti.', 'stock_status' => 'out_of_stock'],
            ['name' => 'Rosemary', 'category' => 'herb', 'unit_price' => 110, 'description' => 'Woody aromatic herb.', 'stock_status' => 'in_stock'],
        ];

        foreach ($plants as $plant) {
            // Insert only if the same plant name does not already exist,
            // keeping the seeder safe to run more than once.
            Plant::firstOrCreate(
                ['name' => $plant['name'], 'user_id' => $user->id],
                $plant
            );
        }
    }
}
