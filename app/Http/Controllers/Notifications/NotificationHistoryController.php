<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationHistory;
use Illuminate\Http\Request;

class NotificationHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = NotificationHistory::with(['notification', 'notification.user'])
            ->orderByDesc('sent_at');

        if ($search) {
            $query->whereHas('notification', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $history = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => Notification::count(),
            'read' => Notification::where('status', 'read')->count(),
            'unread' => Notification::where('status', 'unread')->count(),
            'archived' => Notification::where('status', 'archived')->count(),
        ];

        return view('admin.notifications.notification-history', compact('history', 'stats'));
    }

    public function restore($id)
    {
        $history = NotificationHistory::findOrFail($id);
        $history->notification()->update([
            'status' => 'unread',
            'archived_at' => null,
        ]);

        return back()->with('success', 'Notification restored successfully.');
    }

    public function destroy($id)
    {
        $history = NotificationHistory::findOrFail($id);
        $notification = $history->notification;
        $notification->update(['status' => 'deleted']);
        $history->delete();
        $notification->delete();

        return back()->with('success', 'Notification deleted permanently.');
    }
}
