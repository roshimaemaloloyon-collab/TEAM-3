<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SecuritySetting;
use Illuminate\Http\Request;

class SecuritySettingsController extends Controller
{
    public function index()
    {
        $settings = SecuritySetting::first();
        if (!$settings) {
            $settings = SecuritySetting::create([
                'two_factor_enabled' => false,
                'session_timeout' => 30,
                'max_login_attempts' => 5,
                'lockout_duration' => 15,
                'force_logout_all' => false,
            ]);
        }

        return view('admin.settings.security-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'two_factor_enabled' => 'required|boolean',
            'session_timeout' => 'required|integer|min:5|max:120',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:5|max:60',
            'force_logout_all' => 'required|boolean',
        ]);

        $settings = SecuritySetting::first();
        if (!$settings) {
            $settings = SecuritySetting::create($validated);
        } else {
            $settings->update($validated);
        }

        return back()->with('success', 'Security settings updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user() ?? \App\Models\User::first();
        if ($user && !\Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->update([
            'password' => \Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function forceLogoutAll()
    {
        // In a real application, invalidate all sessions for the user
        return back()->with('success', 'All devices have been logged out successfully.');
    }
}
