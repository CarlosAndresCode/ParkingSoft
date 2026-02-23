<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Subscription;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('vehicle.owner')->latest()->paginate(10);
        $vehicles = Vehicle::whereNotNull('owner_id')->get();

        return view('subscriptions.index', compact('subscriptions', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'months' => 'required|integer|min:1',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $rate = Rate::where('vehicle_type', $vehicle->type)->first();
        $monthlyRate = $rate->monthly_rate ?? 0;

        $startDate = now();
        $endDate = now()->addMonths(intval($validated['months']));

        Subscription::create([
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => $monthlyRate * $validated['months'],
            'status' => 'active',
        ]);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->update(['status' => 'canceled']);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription canceled.');
    }
}
