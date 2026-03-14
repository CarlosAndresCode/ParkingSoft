<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Toyota',
            'Renault',
            'Chevrolet',
            'Mazda',
            'Kia',
            'Suzuki',
            'Nissan',
            'Volkswagen',
            'Ford',
            'Hyundai',
            'Mitsubishi',
            'Honda',
        ];

        foreach ($brands as $brand) {
            \App\Models\Brand::firstOrCreate(['name' => $brand]);
        }
    }
}
