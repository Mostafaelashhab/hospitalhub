<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $clinic = $user->clinic;
        $doctor = $user->isDoctor() ? $user->doctor : null;

        return view('admin.settings.index', compact('user', 'clinic', 'doctor'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $clinic = $user->clinic;
        $section = $request->input('section');

        // ── Profile Section (all roles) ──
        if ($section === 'profile') {
            $rules = [
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
            ];

            if ($request->filled('current_password')) {
                $rules['current_password'] = 'required|current_password';
                $rules['password'] = ['required', 'confirmed', Password::defaults()];
            }

            if ($request->hasFile('avatar')) {
                $rules['avatar'] = 'image|max:2048';
            }

            $validated = $request->validate($rules);

            $user->name = $validated['name'];
            $user->phone = $validated['phone'] ?? $user->phone;

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $request->file('avatar')->store('avatars', 'public');
            }

            if ($request->filled('current_password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return back()->with('success', __('app.profile_updated'));
        }

        // ── Clinic Section (admin only) ──
        if ($section === 'clinic' && $user->isAdmin()) {
            $validated = $request->validate([
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'clinic_phone' => 'required|string|max:20',
                'clinic_email' => 'nullable|email|max:255',
                'address_en' => 'nullable|string|max:500',
                'address_ar' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'working_hours_from' => 'nullable|string',
                'working_hours_to' => 'nullable|string',
                'working_days' => 'nullable|array',
                'working_days.*' => 'in:sat,sun,mon,tue,wed,thu,fri',
            ]);

            if ($request->hasFile('logo')) {
                $request->validate(['logo' => 'image|max:2048']);
                if ($clinic->logo) {
                    Storage::disk('public')->delete($clinic->logo);
                }
                $validated['logo'] = $request->file('logo')->store('clinic-logos', 'public');
            }

            $clinic->update([
                'name_en' => $validated['name_en'],
                'name_ar' => $validated['name_ar'],
                'phone' => $validated['clinic_phone'],
                'email' => $validated['clinic_email'] ?? $clinic->email,
                'address_en' => $validated['address_en'] ?? $clinic->address_en,
                'address_ar' => $validated['address_ar'] ?? $clinic->address_ar,
                'city' => $validated['city'] ?? $clinic->city,
                'logo' => $validated['logo'] ?? $clinic->logo,
                'working_hours_from' => $validated['working_hours_from'] ?? $clinic->working_hours_from,
                'working_hours_to' => $validated['working_hours_to'] ?? $clinic->working_hours_to,
                'working_days' => $validated['working_days'] ?? $clinic->working_days,
            ]);

            return back()->with('success', __('app.clinic_updated'));
        }

        // ── Doctor Settings (doctor role) ──
        if ($section === 'doctor' && $user->isDoctor() && $user->doctor) {
            $validated = $request->validate([
                'consultation_fee' => 'required|numeric|min:0',
                'bio' => 'nullable|string|max:1000',
                'working_days' => 'nullable|array',
                'working_days.*' => 'in:sat,sun,mon,tue,wed,thu,fri',
                'working_from' => 'nullable|date_format:H:i',
                'working_to' => 'nullable|date_format:H:i',
            ]);

            $user->doctor->update([
                'consultation_fee' => $validated['consultation_fee'],
                'bio' => $validated['bio'] ?? $user->doctor->bio,
                'working_days' => $validated['working_days'] ?? [],
                'working_from' => $validated['working_from'],
                'working_to' => $validated['working_to'],
            ]);

            return back()->with('success', __('app.settings_saved'));
        }

        return back();
    }
}
