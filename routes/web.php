<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ParkingSessionController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VehicleController;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('owners', OwnerController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('rates', RateController::class);

    // Parking Sessions
    Route::get('parking', [ParkingSessionController::class, 'index'])->name('parking.index');
    Route::post('parking/check-in', [ParkingSessionController::class, 'checkIn'])->name('parking.check-in');
    Route::post('parking/check-out/{session}', [ParkingSessionController::class, 'checkOut'])->name('parking.check-out');

    // Subscriptions
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::delete('subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
});
