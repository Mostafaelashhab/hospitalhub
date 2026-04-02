<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\DoctorSchedule;
use App\Models\Review;
use Illuminate\Http\Request;

class ClinicPageController extends Controller
{
    public function show(string $slug)
    {
        $clinic = Clinic::where('slug', $slug)
            ->where('website_enabled', true)
            ->where('status', 'active')
            ->with(['specialty', 'doctors' => function ($q) {
                $q->where('is_active', true)->with('specialty');
            }, 'branches'])
            ->firstOrFail();

        $reviews = Review::where('clinic_id', $clinic->id)
            ->where('is_visible', true)
            ->with(['patient', 'doctor'])
            ->latest()
            ->limit(6)
            ->get();
        $avgRating = Review::where('clinic_id', $clinic->id)->where('is_visible', true)->avg('rating');
        $totalReviews = Review::where('clinic_id', $clinic->id)->where('is_visible', true)->count();

        $localeName = app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en;
        $metaTitle = $localeName . ' — ' . __('app.app_name');
        $metaDescription = $clinic->website_meta_description ?? __('app.clinic_page_default_desc', ['name' => $localeName]);
        $ogType = 'business.business';
        $ogImage = $clinic->logo ? asset('storage/' . $clinic->logo) : null;
        $canonicalUrl = route('clinic.page', $clinic->slug);

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Dentist',
            'name' => $localeName,
            'description' => $metaDescription,
            'url' => $canonicalUrl,
            'telephone' => $clinic->phone,
            'email' => $clinic->email,
        ];

        if ($clinic->address_en || $clinic->address_ar) {
            $jsonLd['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => app()->getLocale() === 'ar' ? $clinic->address_ar : $clinic->address_en,
                'addressCountry' => $clinic->country ?? 'EG',
            ];
        }

        if ($clinic->specialty) {
            $jsonLd['medicalSpecialty'] = app()->getLocale() === 'ar'
                ? $clinic->specialty->name_ar
                : $clinic->specialty->name_en;
        }

        if ($clinic->logo) {
            $jsonLd['image'] = asset('storage/' . $clinic->logo);
        }

        return view('clinic.website', compact(
            'clinic', 'metaTitle', 'metaDescription', 'ogType', 'ogImage', 'canonicalUrl', 'jsonLd',
            'reviews', 'avgRating', 'totalReviews'
        ));
    }

    public function availableSlots(Request $request, string $slug)
    {
        $clinic = Clinic::where('slug', $slug)
            ->where('website_enabled', true)
            ->where('status', 'active')
            ->firstOrFail();

        $request->validate([
            'doctor_id' => 'required|integer',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $doctor = Doctor::where('id', $request->doctor_id)
            ->where('clinic_id', $clinic->id)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'slots' => DoctorSchedule::getAvailableSlots($doctor->id, $request->date),
            'on_leave' => DoctorLeave::isOnLeave($doctor->id, $request->date),
        ]);
    }
}
