<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use App\Models\Rate;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ParkingSessionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $activeSessions = ParkingSession::with('vehicle.owner')
            ->where('status', 'active')
            ->when($search, function ($query, $search) {
                return $query->whereHas('vehicle', function ($q) use ($search) {
                    $q->where('plate', 'like', "%{$search}%")
                        ->orWhereHas('owner', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->paginate(4)
            ->withQueryString();

        $recentSessions = ParkingSession::with('vehicle.owner')
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        return view('parking.index', compact('activeSessions', 'recentSessions', 'search'));
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
            Alert::toast('Vehicle is already in the parking lot.', 'error');

            return back();
        }

        ParkingSession::create([
            'vehicle_id' => $vehicle->id,
            'entry_time' => now(),
            'status' => 'active',
        ]);

        Alert::toast('Vehicle checked in successfully.', 'success');

        return redirect()->route('parking.index');
    }

    public function checkOut(ParkingSession $session)
    {
        if ($session->status !== 'active') {
            Alert::toast('Session is already completed.', 'error');

            return back();
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

        Alert::toast('Vehicle checked out. Total price: $'.number_format($totalPrice, 2), 'success');

        return redirect()->route('parking.index');
    }
}
