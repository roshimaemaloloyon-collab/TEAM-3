<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class PerformanceNotificationsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Notification::query()
            ->where('category', 'performance')
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $notifications = $query->paginate($perPage)->withQueryString();

        $stats = [
            'reviews_due' => Notification::where('category', 'performance')->where('type', 'review_due')->count(),
            'kpi_alerts' => Notification::where('category', 'performance')->where('type', 'kpi_alert')->count(),
            'evaluation_reminders' => Notification::where('category', 'performance')->where('type', 'evaluation_reminder')->count(),
            'performance_warnings' => Notification::where('category', 'performance')->where('type', 'low_performance')->count(),
        ];

        $drivers = User::where('role', 'driver')->get(['id', 'name']);

        return view('admin.notifications.performance-notifications', compact('notifications', 'stats', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'type' => 'required|string|in:review_due,kpi_alert,evaluation_reminder,low_performance,recognition_eligibility,promotion_readiness',
            'user_id' => 'nullable|exists:users,id',
            'priority' => 'nullable|string|in:low,normal,high,urgent',
            'channel' => 'nullable|string|in:in-app,email,sms',
        ]);

        $validated['category'] = 'performance';
        $validated['status'] = 'unread';
        $validated['channel'] = $validated['channel'] ?? 'in-app';
        $validated['priority'] = $validated['priority'] ?? 'normal';

        Notification::create($validated);

        return back()->with('success', 'Performance notification sent successfully.');
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update([
            'status' => 'read',
            'read_at' => now(),
        ]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function archive(Notification $notification)
    {
        $notification->update([
            'status' => 'archived',
            'archived_at' => now(),
        ]);

        return back()->with('success', 'Notification archived successfully.');
    }
}
