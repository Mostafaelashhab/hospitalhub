<?php

namespace App\Http\Controllers;

use App\Models\Clinic;

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

        return view('clinic.website', compact('clinic'));
    }
}
