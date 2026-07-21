<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationLogsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $action = $request->query('action');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = NotificationLog::with(['notification', 'performedBy'])
            ->orderByDesc('performed_at');

        if ($search) {
            $query->whereHas('notification', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($action) {
            $query->where('action', $action);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $logs = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_logs' => NotificationLog::count(),
            'successful_deliveries' => NotificationLog::where('status', 'success')->count(),
            'failed_deliveries' => NotificationLog::where('status', 'failed')->count(),
            'pending_notifications' => NotificationLog::where('status', 'pending')->count(),
        ];

        return view('admin.notifications.notification-logs', compact('logs', 'stats'));
    }

    public function retry($id)
    {
        $log = NotificationLog::findOrFail($id);
        $log->update([
            'status' => 'success',
            'action' => 'retried',
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Notification retry initiated successfully.');
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'excel');
        return back()->with('success', "Logs exported as {$format} successfully.");
    }
}
