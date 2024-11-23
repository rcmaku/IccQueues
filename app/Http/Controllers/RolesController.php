<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RolesController extends Controller
{

    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('roles.index', compact('users', 'roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'roleName' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        Role::create([
            'roleName' => $request->roleName,
            'status' => $request->status,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role')); // Form for editing role
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'roleName' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $role->update([
            'roleName' => $request->roleName,
            'status' => $request->status,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->attach($request->role_id);

        return redirect()->route('roles.index')->with('success', 'Role assigned successfully.');
    }



    public function removeRole(Request $request, User $user)
    {
        $user->roles()->detach($request->role);

        return redirect()->route('roles.index')->with('success', 'Role removed successfully.');
    }


    public function list()
    {
        $roles = Role::all();

        return view('roles.list', compact('roles'));
    }
}
