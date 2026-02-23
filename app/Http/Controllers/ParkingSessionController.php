<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use App\Models\Rate;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ParkingSessionController extends Controller
{
    public function index()
    {
        $activeSessions = ParkingSession::with('vehicle.owner')->where('status', 'active')->get();
        $recentSessions = ParkingSession::with('vehicle.owner')->where('status', 'completed')->latest()->take(10)->get();

        return view('parking.index', compact('activeSessions', 'recentSessions'));
    }

    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'plate' => 'required|string',
            'type' => 'required|in:car,motorcycle',
        ]);

        $vehicle = Vehicle::firstOrCreate(
            ['plate' => $validated['plate']],
            ['type' => $validated['type']]
        );

        if ($vehicle->activeParkingSession()) {
            return back()->with('error', 'Vehicle is already in the parking lot.');
        }

        ParkingSession::create([
            'vehicle_id' => $vehicle->id,
            'entry_time' => now(),
            'status' => 'active',
        ]);

        return redirect()->route('parking.index')->with('success', 'Vehicle checked in successfully.');
    }

    public function checkOut(ParkingSession $session)
    {
        if ($session->status !== 'active') {
            return back()->with('error', 'Session is already completed.');
        }

        $exitTime = now();
        $entryTime = $session->entry_time;

        // Calculate duration in seconds
        $durationInSeconds = $entryTime->diffInSeconds($exitTime);

        // Convert to hours and round up
        $durationInHours = (int) ceil($durationInSeconds / 3600);

        if ($durationInHours < 1) {
            $durationInHours = 1;
        }

        $vehicle = $session->vehicle;

        // Check if there is an active subscription
        if ($vehicle->activeSubscription()) {
            $totalPrice = 0;
        } else {
            $rate = Rate::where('vehicle_type', $vehicle->type)->first();
            $totalPrice = $durationInHours * ($rate->hourly_rate ?? 0);
        }

        $session->update([
            'exit_time' => $exitTime,
            'total_price' => $totalPrice,
            'status' => 'completed',
        ]);

        return redirect()->route('parking.index')->with('success', 'Vehicle checked out. Total price: $'.number_format($totalPrice, 2));
    }
}
