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
    public function index()
    {
        $rates = Rate::all();

        return view('rates.index', compact('rates'));
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
}
