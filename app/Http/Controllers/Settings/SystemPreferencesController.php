<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SystemPreference;
use Illuminate\Http\Request;

class SystemPreferencesController extends Controller
{
    public function index()
    {
        $preferences = SystemPreference::first();
        if (!$preferences) {
            $preferences = SystemPreference::create([
                'default_dashboard' => 'admin.dashboard',
                'date_format' => 'M d, Y',
                'time_format' => 'H:i',
                'timezone' => 'Asia/Manila',
                'maintenance_mode' => false,
                'system_version' => '1.0.0',
            ]);
        }

        return view('admin.settings.system-preferences', compact('preferences'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'default_dashboard' => 'required|string|max:255',
            'date_format' => 'required|string|max:50',
            'time_format' => 'required|string|max:50',
            'timezone' => 'required|string|max:100',
            'maintenance_mode' => 'required|boolean',
            'system_version' => 'nullable|string|max:50',
        ]);

        $preferences = SystemPreference::first();
        if (!$preferences) {
            $preferences = SystemPreference::create($validated);
        } else {
            $preferences->update($validated);
        }

        return back()->with('success', 'System preferences updated successfully.');
    }

    public function restoreDefaults()
    {
        $preferences = SystemPreference::first();
        if ($preferences) {
            $preferences->update([
                'default_dashboard' => 'admin.dashboard',
                'date_format' => 'M d, Y',
                'time_format' => 'H:i',
                'timezone' => 'Asia/Manila',
                'maintenance_mode' => false,
                'system_version' => '1.0.0',
            ]);
        }

        return back()->with('success', 'System preferences restored to defaults.');
    }
}
