<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsuranceProvider;
use App\Models\Patient;
use App\Models\PatientInsurance;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    public function index()
    {
        $clinic = auth()->user()->clinic;
        $providers = InsuranceProvider::where('clinic_id', $clinic->id)
            ->withCount('patientInsurances')
            ->latest()
            ->get();

        return view('admin.insurance.index', compact('providers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'coverage_percentage' => 'required|numeric|min:0|max:100',
            'max_coverage' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $clinic = auth()->user()->clinic;
        $clinic->insuranceProviders()->create($validated);

        return back()->with('success', __('app.insurance_provider_created'));
    }

    public function update(Request $request, InsuranceProvider $provider)
    {
        $clinic = auth()->user()->clinic;
        abort_if($provider->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'coverage_percentage' => 'required|numeric|min:0|max:100',
            'max_coverage' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $provider->update($validated);

        return back()->with('success', __('app.insurance_provider_updated'));
    }

    public function destroy(InsuranceProvider $provider)
    {
        $clinic = auth()->user()->clinic;
        abort_if($provider->clinic_id !== $clinic->id, 403);

        $provider->delete();

        return back()->with('success', __('app.insurance_provider_deleted'));
    }

    // Patient Insurance Management
    public function assignToPatient(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'insurance_provider_id' => 'required|exists:insurance_providers,id',
            'policy_number' => 'nullable|string|max:100',
            'member_id' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);

        // Deactivate existing active insurance
        PatientInsurance::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        PatientInsurance::create(array_merge($validated, [
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'is_active' => true,
        ]));

        return back()->with('success', __('app.insurance_assigned'));
    }

    public function removeFromPatient(PatientInsurance $insurance)
    {
        $clinic = auth()->user()->clinic;
        abort_if($insurance->clinic_id !== $clinic->id, 403);

        $insurance->update(['is_active' => false]);

        return back()->with('success', __('app.insurance_removed'));
    }
}
