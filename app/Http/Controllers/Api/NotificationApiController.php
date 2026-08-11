<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationApiController extends ApiController
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = Notification::query();

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($readStatus = $request->input('read')) {
            $query->where('read', $readStatus);
        }

        $perPage = (int) $request->input('per_page', 15);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $notifications = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return $this->success($notifications, 'Notifications retrieved successfully.', 200, [
            'total' => $notifications->total(),
            'current_page' => $notifications->currentPage(),
            'last_page' => $notifications->lastPage(),
            'per_page' => $notifications->perPage(),
        ]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string',
            'recipient_id' => 'nullable|integer',
            'priority' => 'nullable|string',
        ]);

        $notification = Notification::create($validated);

        return $this->success($notification, 'Notification created successfully.', 201);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $notification = Notification::find($id);

        if (! $notification) {
            return $this->error('Notification not found.', 404);
        }

        return $this->success($notification, 'Notification retrieved successfully.');
    }

    public function markAsRead($id): \Illuminate\Http\JsonResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => true]);

        return $this->success($notification, 'Notification marked as read.');
    }

    public function archive($id): \Illuminate\Http\JsonResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['archived' => true]);

        return $this->success($notification, 'Notification archived successfully.');
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return $this->success(null, 'Notification deleted successfully.');
    }
}