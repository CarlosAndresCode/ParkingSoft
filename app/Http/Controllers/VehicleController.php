<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $vehicles = Vehicle::with('owner')
            ->when($search, function ($query, $search) {
                return $query->where('plate', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhereHas('owner', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(10)
            ->withQueryString();

        return view('vehicles.index', compact('vehicles', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $owners = Owner::all();

        return view('vehicles.create', compact('owners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate' => 'required|string|unique:vehicles,plate|max:20',
            'model' => 'nullable|string|max:255',
            'type' => 'required|in:car,motorcycle',
            'owner_id' => 'nullable|exists:owners,id',
        ]);

        Vehicle::create($validated);

        Alert::toast('Vehicle registered successfully.', 'success');

        return redirect()->route('vehicles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $owners = Owner::all();

        return view('vehicles.edit', compact('vehicle', 'owners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate' => 'required|string|max:20|unique:vehicles,plate,'.$vehicle->id,
            'model' => 'nullable|string|max:255',
            'type' => 'required|in:car,motorcycle',
            'owner_id' => 'nullable|exists:owners,id',
        ]);

        $vehicle->update($validated);

        Alert::toast('Vehicle updated successfully.', 'success');

        return redirect()->route('vehicles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        Alert::toast('Vehicle deleted successfully.', 'success');

        return redirect()->route('vehicles.index');
    }
}
