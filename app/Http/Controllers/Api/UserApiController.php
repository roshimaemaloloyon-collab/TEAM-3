<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends ApiController
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->input('per_page', 15);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $users = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return $this->success($users, 'Users retrieved successfully.', 200, [
            'total' => $users->total(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'per_page' => $users->perPage(),
        ]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|max:50',
            'status' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = $validated['status'] ?? 'active';
        $validated['role'] = $validated['role'] ?? 'driver';

        $user = User::create($validated);

        return $this->success($user, 'User successfully created.', 201);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return $this->error('User not found.', 404);
        }

        return $this->success($user, 'User retrieved successfully.');
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string|max:50',
            'status' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return $this->success($user, 'User updated successfully.');
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return $this->success(null, 'User deleted successfully.');
    }

    public function activate($id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return $this->success($user, 'User activated successfully.');
    }

    public function deactivate($id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'inactive']);

        return $this->success($user, 'User deactivated successfully.');
    }
}