<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Subscription;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $subscriptions = Subscription::with('vehicle.owner')
            ->when($search, function ($query, $search) {
                return $query->whereHas('vehicle', function ($q) use ($search) {
                    $q->where('plate', 'like', "%{$search}%")
                        ->orWhereHas('owner', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $vehicles = Vehicle::whereNotNull('owner_id')->get();

        return view('subscriptions.index', compact('subscriptions', 'vehicles', 'search'));
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
            'user_id' => $request->user()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => $monthlyRate * $validated['months'],
            'status' => 'active',
        ]);

        Alert::toast('Subscription created successfully.', 'success');

        return redirect()->route('subscriptions.index');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->update(['status' => 'canceled']);

        Alert::toast('Subscription canceled.', 'success');

        return redirect()->route('subscriptions.index');
    }
}
