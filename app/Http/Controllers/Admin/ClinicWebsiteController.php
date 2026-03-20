<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClinicWebsiteController extends Controller
{
    public function edit()
    {
        $clinic = auth()->user()->clinic;
        return view('admin.website.edit', compact('clinic'));
    }

    public function update(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $validated = $request->validate([
            'website_enabled' => 'boolean',
            'website_primary_color' => 'required|string|max:7',
            'website_secondary_color' => 'required|string|max:7',
            'website_about_en' => 'nullable|string|max:2000',
            'website_about_ar' => 'nullable|string|max:2000',
            'website_services' => 'nullable|array|max:20',
            'website_services.*.name_en' => 'required|string|max:255',
            'website_services.*.name_ar' => 'nullable|string|max:255',
            'website_services.*.icon' => 'nullable|string|max:50',
            'website_social_links' => 'nullable|array',
            'website_social_links.facebook' => 'nullable|url|max:500',
            'website_social_links.instagram' => 'nullable|url|max:500',
            'website_social_links.twitter' => 'nullable|url|max:500',
            'website_social_links.whatsapp' => 'nullable|string|max:20',
            'website_social_links.tiktok' => 'nullable|url|max:500',
            'website_meta_description' => 'nullable|string|max:300',
            'website_show_doctors' => 'boolean',
            'website_show_booking' => 'boolean',
        ]);

        // Handle hero image upload
        if ($request->hasFile('website_hero_image')) {
            $path = $request->file('website_hero_image')->store('clinic-websites', 'public');
            $validated['website_hero_image'] = $path;
        }

        // Handle checkbox defaults
        $validated['website_enabled'] = $request->boolean('website_enabled');
        $validated['website_show_doctors'] = $request->boolean('website_show_doctors');
        $validated['website_show_booking'] = $request->boolean('website_show_booking');

        // Filter empty services
        if (isset($validated['website_services'])) {
            $validated['website_services'] = array_values(array_filter($validated['website_services'], fn($s) => !empty($s['name_en'])));
        }

        // Filter empty social links
        if (isset($validated['website_social_links'])) {
            $validated['website_social_links'] = array_filter($validated['website_social_links']);
        }

        $clinic->update($validated);

        return back()->with('success', __('app.website_settings_saved'));
    }
}
