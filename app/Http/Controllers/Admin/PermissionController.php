<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    // Show permissions UI
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('admin.permissions', compact('roles', 'permissions'));
    }

    // Assign permissions to role
    public function update(Request $request, Role $role)
    {
        $role->permissions()->sync($request->permissions ?? []);
        return redirect()->back()->with('success', 'Permissions updated successfully!');
    }
}
