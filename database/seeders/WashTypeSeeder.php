<?php

namespace Database\Seeders;

use App\Models\WashType;
use Illuminate\Database\Seeder;

class WashTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Lavado Sencillo (Carro)',
                'price' => 20000,
                'vehicle_type' => 'car',
            ],
            [
                'name' => 'Lavado Completo (SUV)',
                'price' => 35000,
                'vehicle_type' => 'suv',
            ],
            [
                'name' => 'Lavado Sencillo (Moto)',
                'price' => 12000,
                'vehicle_type' => 'motorcycle',
            ],
            [
                'name' => 'Lavado con Polichado (Carro)',
                'price' => 50000,
                'vehicle_type' => 'car',
            ],
        ];

        foreach ($types as $type) {
            WashType::create($type);
        }
    }
}
