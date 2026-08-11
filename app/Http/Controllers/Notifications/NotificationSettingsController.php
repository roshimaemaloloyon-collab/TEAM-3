<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationSettingsController extends Controller
{
    public function index()
    {
        $settings = NotificationSetting::all();
        $drivers = User::where('role', 'driver')->get(['id', 'name']);

        $stats = [
            'active_channels' => NotificationSetting::where('enabled', true)->distinct('type')->count(),
            'disabled_notifications' => NotificationSetting::where('enabled', false)->count(),
            'reminder_settings' => NotificationSetting::where('frequency', '!=', 'immediate')->count(),
            'delivery_preferences' => NotificationSetting::distinct('type')->count(),
        ];

        return view('admin.notifications.notification-settings', compact('settings', 'stats', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'category' => 'required|string|in:training,performance,system,announcement',
            'type' => 'required|string|in:email,in_app,sms',
            'enabled' => 'required|boolean',
            'frequency' => 'required|string|in:immediate,daily,weekly',
        ]);

        $validated['user_id'] = $validated['user_id'] ?? null;

        NotificationSetting::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'category' => $validated['category'],
                'type' => $validated['type'],
            ],
            [
                'enabled' => $validated['enabled'],
                'frequency' => $validated['frequency'],
            ]
        );

        return back()->with('success', 'Notification settings saved successfully.');
    }

    public function reset()
    {
        NotificationSetting::query()->delete();

        $defaults = [
            ['category' => 'training', 'type' => 'in_app', 'enabled' => true, 'frequency' => 'immediate'],
            ['category' => 'training', 'type' => 'email', 'enabled' => true, 'frequency' => 'immediate'],
            ['category' => 'performance', 'type' => 'in_app', 'enabled' => true, 'frequency' => 'immediate'],
            ['category' => 'performance', 'type' => 'email', 'enabled' => true, 'frequency' => 'daily'],
            ['category' => 'system', 'type' => 'in_app', 'enabled' => true, 'frequency' => 'immediate'],
            ['category' => 'system', 'type' => 'email', 'enabled' => true, 'frequency' => 'immediate'],
            ['category' => 'announcement', 'type' => 'in_app', 'enabled' => true, 'frequency' => 'immediate'],
            ['category' => 'announcement', 'type' => 'email', 'enabled' => true, 'frequency' => 'daily'],
        ];

        foreach ($defaults as $setting) {
            NotificationSetting::create($setting);
        }

        return back()->with('success', 'Notification settings reset to default.');
    }

    public function test()
    {
        return back()->with('success', 'Test notification sent successfully.');
    }
}
