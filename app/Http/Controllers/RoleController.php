<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load relasi users dan permissions agar count tidak error di view
        $roles = Role::with(['users', 'permissions'])->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name'   => 'required|string|max:255|unique:roles,role_name',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id_permission'
        ]);

        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role = Role::create($validated);

        if (!empty($permissions)) {
            $role->permissions()->attach($permissions);
        }

        return redirect()->route('user-roles.index')->with('success', 'Role created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $user_role)  
    {
        $user_role->load(['users', 'permissions']);  
        return view('roles.show', compact('user_role')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $user_role)
    {
        $permissions = Permission::all();
        $user_role->load('permissions');
        return view('roles.edit', compact('user_role', 'permissions')); // ✅
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $user_role)
    {
        $validated = $request->validate([
            'role_name'   => 'required|string|max:255|unique:roles,role_name,' . $user_role->id_role,
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id_permission'
        ]);

        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $user_role->update($validated);
        $user_role->permissions()->sync($permissions);

        return redirect()->route('user-roles.index')->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $user_role)
    {
        if ($user_role->users()->exists()) {
            return redirect()->route('user-roles.index')->with('error', 'Cannot delete role with existing users.');
        }

        $user_role->permissions()->detach();
        $user_role->delete();

        return redirect()->route('user-roles.index')->with('success', 'Role deleted successfully!');
    }
}
