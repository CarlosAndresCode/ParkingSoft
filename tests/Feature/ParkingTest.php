<?php

use App\Models\ParkingSession;
use App\Models\Rate;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can access parking dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/parking');

    $response->assertStatus(200);
});

test('can check in a vehicle', function () {
    $user = User::factory()->create();
    Rate::create(['vehicle_type' => 'car', 'hourly_rate' => 5, 'monthly_rate' => 100]);

    $response = $this->actingAs($user)->post('/parking/check-in', [
        'plate' => 'ABC-123',
        'type' => 'car',
    ]);

    $response->assertRedirect('/parking');
    $this->assertDatabaseHas('vehicles', ['plate' => 'ABC-123']);
    $this->assertDatabaseHas('parking_sessions', ['status' => 'active']);
});

test('can check out a vehicle and calculates price', function () {
    $user = User::factory()->create();
    $rate = Rate::create(['vehicle_type' => 'car', 'hourly_rate' => 10, 'monthly_rate' => 100]);
    $vehicle = Vehicle::create(['plate' => 'XYZ-789', 'type' => 'car']);

    // We use a fixed time to avoid subHours issues with ceil
    $entryTime = now()->subMinutes(120)->addSeconds(5); // A bit less than 120 minutes to be safe

    $session = ParkingSession::create([
        'vehicle_id' => $vehicle->id,
        'entry_time' => $entryTime,
        'status' => 'active',
    ]);

    $response = $this->actingAs($user)->post("/parking/check-out/{$session->id}");

    $response->assertRedirect('/parking');
    $session->refresh();
    expect($session->status)->toBe('completed');
    // 2 hours * 10 = 20
    expect((float) $session->total_price)->toBe(20.0);
});
