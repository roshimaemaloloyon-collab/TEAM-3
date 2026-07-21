<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting;
use Illuminate\Http\Request;

class AgencySettingsController extends Controller
{
    public function index()
    {
        $settings = AgencySetting::first();
        if (!$settings) {
            $settings = AgencySetting::create([
                'agency_name' => 'TripWise Driver Performance System',
                'email' => 'info@tripwise.app',
            ]);
        }

        return view('admin.settings.agency-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'agency_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:1000',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $settings = AgencySetting::first();
        if (!$settings) {
            $settings = AgencySetting::create($validated);
        } else {
            $settings->update($validated);
        }

        return back()->with('success', 'Agency settings updated successfully.');
    }
}
