<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $rates = Rate::query()
            ->when($search, function ($query, $search) {
                return $query->where('vehicle_type', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('rates.index', compact('rates', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|string|max:50|unique:rates,vehicle_type',
            'hourly_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0',
        ]);

        Rate::create($validated);

        Alert::toast('Rate created successfully.', 'success');

        return redirect()->route('rates.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rate $rate)
    {
        return view('rates.edit', compact('rate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rate $rate)
    {
        $validated = $request->validate([
            'hourly_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0',
        ]);

        $rate->update($validated);

        Alert::toast('Rate updated successfully.', 'success');

        return redirect()->route('rates.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate)
    {
        $rate->delete();

        Alert::toast('Rate deleted successfully.', 'success');

        return redirect()->route('rates.index');
    }
}
