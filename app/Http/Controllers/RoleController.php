<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'is_active' => 'boolean',
        ]);

        Role::create($validated);

        Alert::toast('Rol creado correctamente.', 'success');

        return redirect()->route('roles.index');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,'.$role->id,
            'is_active' => 'boolean',
        ]);

        $role->update($validated);

        Alert::toast('Rol actualizado correctamente.', 'success');

        return redirect()->route('roles.index');
    }

    public function toggle(Role $role)
    {
        $role->update(['is_active' => ! $role->is_active]);

        Alert::toast('Estado del rol actualizado.', 'success');

        return back();
    }
}
