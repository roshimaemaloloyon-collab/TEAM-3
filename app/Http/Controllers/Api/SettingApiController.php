<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\AgencySetting;
use App\Models\SystemPreference;
use App\Models\SecuritySetting;
use Illuminate\Http\Request;

class SettingApiController extends ApiController
{
    public function agency(): \Illuminate\Http\JsonResponse
    {
        $settings = AgencySetting::first();

        return $this->success($settings ?? [], 'Agency settings retrieved successfully.');
    }

    public function updateAgency(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'logo' => 'nullable|string',
        ]);

        $settings = AgencySetting::firstOrCreate([]);
        $settings->update($validated);

        return $this->success($settings, 'Agency settings updated successfully.');
    }

    public function preferences(): \Illuminate\Http\JsonResponse
    {
        $preferences = SystemPreference::first();

        return $this->success($preferences ?? [], 'System preferences retrieved successfully.');
    }

    public function updatePreferences(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'theme' => 'nullable|string',
            'language' => 'nullable|string',
            'timezone' => 'nullable|string',
            'items_per_page' => 'nullable|integer',
        ]);

        $preferences = SystemPreference::firstOrCreate([]);
        $preferences->update($validated);

        return $this->success($preferences, 'Preferences updated successfully.');
    }

    public function security(): \Illuminate\Http\JsonResponse
    {
        $settings = SecuritySetting::first();

        return $this->success($settings ?? [], 'Security settings retrieved successfully.');
    }

    public function updateSecurity(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'session_timeout' => 'nullable|integer',
            'max_login_attempts' => 'nullable|integer',
            'lockout_duration' => 'nullable|integer',
            'two_factor_enabled' => 'nullable|boolean',
            'password_min_length' => 'nullable|integer',
        ]);

        $settings = SecuritySetting::firstOrCreate([]);
        $settings->update($validated);

        return $this->success($settings, 'Security settings updated successfully.');
    }
}