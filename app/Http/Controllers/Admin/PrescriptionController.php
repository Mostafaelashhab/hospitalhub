<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;

class PrescriptionController extends Controller
{
    public function print(Prescription $prescription)
    {
        $clinic = auth()->user()->clinic;
        abort_if($prescription->clinic_id !== $clinic->id, 403);

        $prescription->load(['items', 'patient', 'doctor', 'diagnosis.appointment', 'clinic']);

        return view('admin.prescriptions.print', compact('prescription', 'clinic'));
    }
}
