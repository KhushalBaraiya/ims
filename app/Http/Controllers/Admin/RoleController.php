<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->get();
        return view('admin.role.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.role.create');
    }

    public function show($id)
    {
        $role = Role::withCount('users')->with('users')->findOrFail($id);
        return view('admin.role.show', compact('role'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Role::create([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.role.index')
            ->with('success', 'Role added successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.role.create', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $role->update([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.role.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()
            ->route('admin.role.index')
            ->with('success', 'Role deleted successfully.');
    }
}