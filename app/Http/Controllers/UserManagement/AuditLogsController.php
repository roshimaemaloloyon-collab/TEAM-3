<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $module = $request->query('module');
        $action = $request->query('action');
        $perPage = (int) ($request->query('per_page', 15));

        $query = AuditLog::with('user')->orderByDesc('performed_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($module) {
            $query->where('module', $module);
        }

        if ($action) {
            $query->where('action', $action);
        }

        $logs = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => AuditLog::count(),
            'user_changes' => AuditLog::where('module', 'users')->count(),
            'permission_changes' => AuditLog::where('module', 'roles')->count(),
            'admin_actions' => AuditLog::where('action', 'updated')->count(),
        ];

        return view('admin.user-management.audit-logs', compact('logs', 'stats'));
    }
}
