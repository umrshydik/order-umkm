<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view hak akses', only: ['index', 'show']),
            new Middleware('permission:create hak akses', only: ['create', 'store']),
            new Middleware('permission:edit hak akses', only: ['edit', 'update']),
            new Middleware('permission:delete hak akses', only: ['destroy']),
        ];
    }
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            array_shift($parts); // remove the action (view, create, etc)
            return implode(' ', $parts); // return the rest as menu name
        });
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Hak akses berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            array_shift($parts); // remove the action (view, create, etc)
            return implode(' ', $parts); // return the rest as menu name
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Hak akses berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->route('admin.roles.index')->with('error', 'Role admin tidak dapat dihapus.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Hak akses berhasil dihapus.');
    }
}
