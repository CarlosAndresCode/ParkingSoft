<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $owners = Owner::withCount('vehicles')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('owners.index', compact('owners', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:owners,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Owner::create($validated);

        Alert::toast('Owner created successfully.', 'success');

        return redirect()->route('owners.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Owner $owner)
    {
        $owner->load('vehicles');

        return view('owners.show', compact('owner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Owner $owner)
    {
        return view('owners.edit', compact('owner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Owner $owner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:owners,email,'.$owner->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $owner->update($validated);

        Alert::toast('Owner updated successfully.', 'success');

        return redirect()->route('owners.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Owner $owner)
    {
        $owner->delete();

        Alert::toast('Owner deleted successfully.', 'success');

        return redirect()->route('owners.index');
    }
}
