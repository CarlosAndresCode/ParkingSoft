<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use App\Models\Rate;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ParkingSessionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $activeSessions = ParkingSession::with(['vehicle.owner', 'vehicle.brand'])
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

        $recentSessions = ParkingSession::with(['vehicle.owner', 'vehicle.brand'])
            ->where('status', 'completed')
            ->whereDate('exit_time', now()->toDateString())
            ->latest()
            ->take(25)
            ->get();



        return view('parking.index', compact('activeSessions', 'recentSessions', 'search'));
    }

    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'plate' => 'required|string',
            'type' => 'required|in:car,motorcycle',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        $vehicle = Vehicle::firstOrCreate(
            ['plate' => strtoupper($validated['plate'])],
            [
                'type' => $validated['type'],
                'brand_id' => $validated['brand_id'] ?? null,
            ]
        );

        if ($vehicle->activeParkingSession()) {
            Alert::toast('Vehicle is already in the parking lot.', 'error');

            return back();
        }

        $session = ParkingSession::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => $request->user()->id,
            'entry_time' => now(),
            'status' => 'active',
        ]);

        Alert::toast('Vehicle checked in successfully.', 'success');

        return redirect()->route('parking.index')
                ->with('pdf_session_id', $session); // 👈 Solo pasamos el ID
    }

    public function printTicket(ParkingSession $session)
    {
        $session->load('vehicle.brand');

        $pdf = Pdf::loadView('tickets.parking-entry', compact('session'))
            ->setPaper([0, 0, 226.77, 425.20], 'portrait');

        return $pdf->stream('ticket-' . $session->id . '.pdf');
    }

    public function checkOut(ParkingSession $session)
    {
        if ($session->status !== 'active') {
            Alert::toast('Session is already completed.', 'error');

            return back();
        }

        $totalPrice = $this->calculateTotalPrice($session);
        $exitTime = now();
        $user = Auth()->user();

        $session->update([
            'exit_time' => $exitTime,
            'total_price' => $totalPrice,
            'status' => 'completed',
            'user_id' => $user->id,
        ]);

        Alert::toast('Vehicle checked out. Total price: $'.number_format($totalPrice, 2), 'success');

        return redirect()->route('parking.index');
    }

    public function calculatePrice(ParkingSession $session)
    {
        $price = $this->calculateTotalPrice($session);

        return response()->json([
            'price' => $price,
            'formatted_price' => number_format($price, 2),
        ]);
    }

    private function calculateTotalPrice(ParkingSession $session): float
    {
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
            return 0;
        }

        $rate = Rate::where('vehicle_type', $vehicle->type)->first();

        return (float) ($durationInHours * ($rate->hourly_rate ?? 0));
    }
}
