<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Rate::create([
            'vehicle_type' => 'car',
            'hourly_rate' => 5.00,
            'monthly_rate' => 100.00,
        ]);

        \App\Models\Rate::create([
            'vehicle_type' => 'motorcycle',
            'hourly_rate' => 2.00,
            'monthly_rate' => 50.00,
        ]);
    }
}
