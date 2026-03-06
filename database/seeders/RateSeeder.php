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
            'hourly_rate' => 2000,
            'monthly_rate' => 80000,
        ]);

        \App\Models\Rate::create([
            'vehicle_type' => 'motorcycle',
            'hourly_rate' => 1000,
            'monthly_rate' => 40000,
        ]);
    }
}
