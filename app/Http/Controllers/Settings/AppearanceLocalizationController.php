<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\AppearanceSetting;
use Illuminate\Http\Request;

class AppearanceLocalizationController extends Controller
{
    public function index()
    {
        $settings = AppearanceSetting::first();
        if (!$settings) {
            $settings = AppearanceSetting::create([
                'theme' => 'light',
                'language' => 'en',
                'font_size' => 'medium',
                'sidebar_behavior' => 'expanded',
                'high_contrast' => false,
                'reduce_motion' => false,
            ]);
        }

        return view('admin.settings.appearance-localization', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|string|in:light,dark',
            'language' => 'required|string|max:10',
            'font_size' => 'required|string|in:small,medium,large',
            'sidebar_behavior' => 'required|string|in:expanded,collapsed',
            'high_contrast' => 'required|boolean',
            'reduce_motion' => 'required|boolean',
        ]);

        $settings = AppearanceSetting::first();
        if (!$settings) {
            $settings = AppearanceSetting::create($validated);
        } else {
            $settings->update($validated);
        }

        return back()->with('success', 'Appearance settings updated successfully.');
    }

    public function restoreDefaults()
    {
        $settings = AppearanceSetting::first();
        if ($settings) {
            $settings->update([
                'theme' => 'light',
                'language' => 'en',
                'font_size' => 'medium',
                'sidebar_behavior' => 'expanded',
                'high_contrast' => false,
                'reduce_motion' => false,
            ]);
        }

        return back()->with('success', 'Appearance settings restored to defaults.');
    }
}
