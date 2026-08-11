<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRolesPermissionsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Role::query()->orderBy('name');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $roles = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => Role::count(),
            'active' => Role::where('is_active', true)->count(),
            'assigned' => UserRole::distinct('role_id')->count(),
            'permissions' => Permission::count(),
        ];

        $permissions = Permission::all();
        $users = User::all(['id', 'name', 'email']);

        return view('admin.user-management.user-roles-permissions', compact('roles', 'stats', 'permissions', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $validated['is_active'] = true;
        $validated['created_by'] = auth()->id() ?? 1;

        $role = Role::create($validated);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return back()->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'is_active' => 'required|boolean',
        ]);

        $role->update($validated);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->detach();
        }

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->permissions()->detach();
        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}
