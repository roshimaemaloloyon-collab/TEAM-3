<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class LoginActivityLogsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = UserActivityLog::with('user')->orderByDesc('performed_at');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('action', $status);
        }

        $logs = $query->paginate($perPage)->withQueryString();

        $stats = [
            'today_logins' => UserActivityLog::where('action', 'logged_in')->whereDate('performed_at', today())->count(),
            'failed_logins' => LoginLog::where('status', 'failed')->whereDate('login_at', today())->count(),
            'active_sessions' => 0,
            'user_activities' => UserActivityLog::whereDate('performed_at', today())->count(),
        ];

        $users = User::all(['id', 'name', 'email']);

        return view('admin.user-management.login-activity-logs', compact('logs', 'stats', 'users'));
    }
}
