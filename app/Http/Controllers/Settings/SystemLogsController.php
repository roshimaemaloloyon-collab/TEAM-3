<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use Illuminate\Http\Request;

class SystemLogsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $eventType = $request->query('event_type');
        $severity = $request->query('severity');
        $perPage = (int) ($request->query('per_page', 15));

        $query = SystemLog::with('user')->orderByDesc('performed_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
            });
        }

        if ($eventType) {
            $query->where('event_type', $eventType);
        }

        if ($severity) {
            $query->where('severity', $severity);
        }

        $logs = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_logs' => SystemLog::count(),
            'errors_today' => SystemLog::where('severity', 'error')->whereDate('performed_at', today())->count(),
            'system_events' => SystemLog::where('event_type', 'system_activity')->count(),
            'config_updates' => SystemLog::where('event_type', 'configuration_change')->count(),
        ];

        return view('admin.settings.system-logs', compact('logs', 'stats'));
    }
}
