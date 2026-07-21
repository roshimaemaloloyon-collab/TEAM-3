<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserAccountsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = User::query()->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $users = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'drivers' => User::where('role', 'driver')->count(),
            'active' => User::where('status', 'active')->count(),
        ];

        return view('admin.user-management.user-accounts', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,driver',
            'status' => 'required|in:active,inactive,suspended,archived',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        return back()->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->get(['id', 'name', 'slug']);
        return view('admin.user-management.partials.edit-user', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,driver',
            'status' => 'required|in:active,inactive,suspended,archived',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->update(['status' => 'archived']);
        $user->delete();

        return back()->with('success', 'User archived successfully.');
    }
}
