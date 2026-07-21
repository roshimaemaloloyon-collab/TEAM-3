<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;

class SecurityMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $eventType = $request->query('event_type');
        $severity = $request->query('severity');
        $perPage = (int) ($request->query('per_page', 15));

        $query = LoginLog::with('user')->orderByDesc('login_at');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($eventType) {
            $query->where('status', $eventType);
        }

        if ($severity) {
            $query->where('failure_reason', 'like', "%{$severity}%");
        }

        $logs = $query->paginate($perPage)->withQueryString();

        $stats = [
            'failed_logins' => LoginLog::where('status', 'failed')->count(),
            'suspicious_activities' => LoginLog::where('status', 'blocked')->count(),
            'locked_accounts' => User::where('status', 'suspended')->count(),
            'security_alerts' => LoginLog::where('status', 'failed')->whereDate('login_at', today())->count(),
        ];

        $users = User::all(['id', 'name', 'email']);

        return view('admin.user-management.security-monitoring', compact('logs', 'stats', 'users'));
    }
}
