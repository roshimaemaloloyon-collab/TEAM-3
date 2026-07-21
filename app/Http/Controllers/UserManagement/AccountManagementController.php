<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AccountManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = User::query()->orderByDesc('updated_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $users = $query->paginate($perPage)->withQueryString();

        $stats = [
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'locked' => User::where('status', 'suspended')->count(),
            'password_resets' => 0,
        ];

        return view('admin.user-management.account-management', compact('users', 'stats'));
    }

    public function activate(User $user)
    {
        $user->update(['status' => 'active']);

        return back()->with('success', 'Account activated successfully.');
    }

    public function deactivate(User $user)
    {
        $user->update(['status' => 'inactive']);

        return back()->with('success', 'Account deactivated successfully.');
    }

    public function lock(User $user)
    {
        $user->update(['status' => 'suspended']);

        return back()->with('success', 'Account locked successfully.');
    }

    public function unlock(User $user)
    {
        $user->update(['status' => 'active']);

        return back()->with('success', 'Account unlocked successfully.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($request->input('new_password')),
        ]);

        return back()->with('success', 'Password reset successfully.');
    }
}
