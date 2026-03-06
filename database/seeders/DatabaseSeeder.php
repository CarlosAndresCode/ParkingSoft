<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create(); // Create user admin default

        $this->call([
            RateSeeder::class
        ]);

        if(config('app.env') === 'local') { // Demo user for testing
            User::create([
                'name' => 'Cashier User',
                'email' => 'cashier@cahier.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 2, // Cashier
            ]);
        }
    }
}
