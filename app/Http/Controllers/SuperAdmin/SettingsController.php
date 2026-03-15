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
            'instapay_account_name' => PlatformSetting::get('instapay_account_name'),
            'instapay_account_number' => PlatformSetting::get('instapay_account_number'),
            'vodafone_account_name' => PlatformSetting::get('vodafone_account_name'),
            'vodafone_account_number' => PlatformSetting::get('vodafone_account_number'),
        ];

        return view('super-admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'point_price' => 'required|numeric|min:0',
            'free_mode_enabled' => 'nullable|boolean',
            'free_mode_until' => 'nullable|date|after_or_equal:today',
            'instapay_account_name' => 'nullable|string|max:255',
            'instapay_account_number' => 'nullable|string|max:255',
            'vodafone_account_name' => 'nullable|string|max:255',
            'vodafone_account_number' => 'nullable|string|max:255',
        ]);

        PlatformSetting::set('point_price', $validated['point_price']);
        PlatformSetting::set('free_mode_enabled', $request->has('free_mode_enabled') ? '1' : '0');
        PlatformSetting::set('free_mode_until', $validated['free_mode_until'] ?? null);
        PlatformSetting::set('instapay_account_name', $validated['instapay_account_name'] ?? null);
        PlatformSetting::set('instapay_account_number', $validated['instapay_account_number'] ?? null);
        PlatformSetting::set('vodafone_account_name', $validated['vodafone_account_name'] ?? null);
        PlatformSetting::set('vodafone_account_number', $validated['vodafone_account_number'] ?? null);

        return back()->with('success', __('app.settings_updated'));
    }
}
