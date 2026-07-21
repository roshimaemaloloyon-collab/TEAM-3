<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class TrainingNotificationsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Notification::query()
            ->where('category', 'training')
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
            'upcoming' => Notification::where('category', 'training')->where('type', 'upcoming')->count(),
            'schedule_changes' => Notification::where('category', 'training')->where('type', 'schedule_change')->count(),
            'attendance_reminders' => Notification::where('category', 'training')->where('type', 'attendance_reminder')->count(),
            'certificates' => Notification::where('category', 'training')->where('type', 'certificate')->count(),
        ];

        $drivers = User::where('role', 'driver')->get(['id', 'name']);

        return view('admin.notifications.training-notifications', compact('notifications', 'stats', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'type' => 'required|string|in:upcoming,schedule_change,attendance_reminder,certificate,missed_training',
            'user_id' => 'nullable|exists:users,id',
            'priority' => 'nullable|string|in:low,normal,high,urgent',
            'channel' => 'nullable|string|in:in-app,email,sms',
            'expires_at' => 'nullable|date',
        ]);

        $validated['category'] = 'training';
        $validated['status'] = 'unread';
        $validated['channel'] = $validated['channel'] ?? 'in-app';
        $validated['priority'] = $validated['priority'] ?? 'normal';

        Notification::create($validated);

        return back()->with('success', 'Training notification sent successfully.');
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

    public function destroy(Notification $notification)
    {
        $notification->update(['status' => 'deleted']);
        $notification->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }
}
