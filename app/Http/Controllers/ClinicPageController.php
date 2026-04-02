<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\DoctorSchedule;
use App\Models\Review;
use App\Services\Public\ClinicPageService;
use Illuminate\Http\Request;

class ClinicPageController extends Controller
{
    public function __construct(protected ClinicPageService $clinicPageService)
    {}
    public function show(string $slug)
    {
    
        $data = $this->clinicPageService->show($slug);
        return view('clinic.website', $data);
    }

    public function availableSlots(Request $request, string $slug)
    {
        $slotData = $this->clinicPageService->availableSlots($request, $slug);
        return response()->json($slotData);
    }
}
