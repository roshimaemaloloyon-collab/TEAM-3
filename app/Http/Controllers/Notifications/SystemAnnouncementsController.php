<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class SystemAnnouncementsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Notification::query()
            ->where('category', 'system')
            ->orderByDesc('created_at');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('type', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $announcements = $query->paginate($perPage)->withQueryString();

        $stats = [
            'active' => Notification::where('category', 'system')->where('status', '!=', 'archived')->count(),
            'scheduled' => Notification::where('category', 'system')->where('status', 'scheduled')->count(),
            'maintenance' => Notification::where('category', 'system')->where('type', 'maintenance')->count(),
            'system_updates' => Notification::where('category', 'system')->where('type', 'system_update')->count(),
        ];

        return view('admin.notifications.system-announcements', compact('announcements', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'type' => 'required|string|in:agency,system_update,maintenance,policy,emergency,general',
            'priority' => 'nullable|string|in:low,normal,high,urgent',
            'channel' => 'nullable|string|in:in-app,email,sms',
            'expires_at' => 'nullable|date',
        ]);

        $validated['category'] = 'system';
        $validated['status'] = 'unread';
        $validated['channel'] = $validated['channel'] ?? 'in-app';
        $validated['priority'] = $validated['priority'] ?? 'normal';

        Notification::create($validated);

        return back()->with('success', 'Announcement created successfully.');
    }

    public function publish(Notification $announcement)
    {
        $announcement->update(['status' => 'published']);

        return back()->with('success', 'Announcement published successfully.');
    }

    public function archive(Notification $announcement)
    {
        $announcement->update([
            'status' => 'archived',
            'archived_at' => now(),
        ]);

        return back()->with('success', 'Announcement archived successfully.');
    }

    public function destroy(Notification $announcement)
    {
        $announcement->update(['status' => 'deleted']);
        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully.');
    }
}
