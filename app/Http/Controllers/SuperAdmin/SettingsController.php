<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'point_price' => PlatformSetting::get('point_price', 1),
            'free_mode_enabled' => PlatformSetting::get('free_mode_enabled', '0'),
            'free_mode_until' => PlatformSetting::get('free_mode_until'),
        ];

        return view('super-admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'point_price' => 'required|numeric|min:0',
            'free_mode_enabled' => 'nullable|boolean',
            'free_mode_until' => 'nullable|date|after_or_equal:today',
        ]);

        PlatformSetting::set('point_price', $validated['point_price']);
        PlatformSetting::set('free_mode_enabled', $request->has('free_mode_enabled') ? '1' : '0');
        PlatformSetting::set('free_mode_until', $validated['free_mode_until'] ?? null);

        return back()->with('success', __('app.settings_updated'));
    }
}
