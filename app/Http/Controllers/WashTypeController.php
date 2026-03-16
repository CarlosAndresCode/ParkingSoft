<?php

namespace App\Http\Controllers;

use App\Models\WashType;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WashTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $washTypes = WashType::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('vehicle_type', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('wash_types.index', compact('washTypes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('wash_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'vehicle_type' => 'required|string|max:50',
        ]);

        WashType::create($validated);

        Alert::toast('Tipo de lavado creado correctamente.', 'success');

        return redirect()->route('wash-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WashType $washType)
    {
        return view('wash_types.edit', compact('washType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WashType $washType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'vehicle_type' => 'required|string|max:50',
        ]);

        $washType->update($validated);

        Alert::toast('Tipo de lavado actualizado correctamente.', 'success');

        return redirect()->route('wash-types.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WashType $washType)
    {
        $washType->delete();

        return redirect()->route('wash-types.index')->with('success', 'Tipo de lavado eliminado correctamente.');
    }
}
