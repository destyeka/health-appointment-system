<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,permission_name',
            'description' => 'nullable|string',
        ]);

        Permission::create($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil ditambahkan!');
    }

    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,permission_name,' . $permission->id_permission . ',id_permission',
            'description' => 'nullable|string',
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil diperbarui!');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus!');
    }
}
