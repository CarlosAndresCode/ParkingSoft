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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('owners', OwnerController::class);
    Route::resource('vehicles', VehicleController::class);

    // Rutas de configuración (solo admin)
    Route::middleware('role:admin')->group(function () {
        Route::resource('rates', RateController::class);
        Route::resource('roles', \App\Http\Controllers\RoleController::class)->except(['show', 'destroy']);
        Route::post('roles/{role}/toggle', [\App\Http\Controllers\RoleController::class, 'toggle'])->name('roles.toggle');
        Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show', 'destroy']);
    });

    // Caja
    Route::get('cash', [\App\Http\Controllers\CashRegisterController::class, 'show'])->name('cash-register.show');
    Route::post('cash/open', [\App\Http\Controllers\CashRegisterController::class, 'open'])->name('cash-register.open');
    Route::post('cash/close', [\App\Http\Controllers\CashRegisterController::class, 'close'])->name('cash-register.close');

    // Parking Sessions
    Route::get('parking', [ParkingSessionController::class, 'index'])->name('parking.index');
    Route::post('parking/check-in', [ParkingSessionController::class, 'checkIn'])->middleware('cash.open')->name('parking.check-in');
    Route::post('parking/check-out/{session}', [ParkingSessionController::class, 'checkOut'])->middleware('cash.open')->name('parking.check-out');

    // Subscriptions
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('subscriptions', [SubscriptionController::class, 'store'])->middleware('cash.open')->name('subscriptions.store');
    Route::delete('subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
});
