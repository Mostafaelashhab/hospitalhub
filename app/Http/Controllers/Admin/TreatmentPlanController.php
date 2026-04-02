<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\DentalChart;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use Illuminate\Http\Request;

class TreatmentPlanController extends Controller
{
    public function index(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $plans = $patient->treatmentPlans()
            ->with('doctor', 'items')
            ->latest()
            ->get();

        return view('admin.treatment-plans.index', compact('patient', 'plans'));
    }

    public function create(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $doctors = $clinic->doctors()->where('is_active', true)->get();
        $latestChart = $patient->latestDentalChart;
        $toothNumbers = DentalChart::allToothNumbers();

        return view('admin.treatment-plans.create', compact('patient', 'doctors', 'latestChart', 'toothNumbers'));
    }

    public function store(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string|max:2000',
            'doctor_id' => 'required|exists:doctors,id',
            'discount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.tooth_number' => 'nullable|string',
            'items.*.service_id' => 'nullable|exists:services,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.estimated_cost' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string|max:500',
        ]);

        $plan = TreatmentPlan::create([
            'clinic_id' => $clinic->id,
            'branch_id' => BranchHelper::activeBranchId(),
            'patient_id' => $patient->id,
            'doctor_id' => $validated['doctor_id'],
            'title' => $validated['title'],
            'notes' => $validated['notes'] ?? null,
            'discount' => $validated['discount'] ?? 0,
            'status' => 'draft',
        ]);

        foreach ($validated['items'] as $i => $item) {
            $plan->items()->create([
                'tooth_number' => $item['tooth_number'] ?: null,
                'service_id' => $item['service_id'] ?? null,
                'description' => $item['description'],
                'estimated_cost' => $item['estimated_cost'],
                'notes' => $item['notes'] ?? null,
                'sort_order' => $i,
            ]);
        }

        $plan->recalculateTotal();

        $route = auth()->user()->role === 'doctor' && request()->is('doctor/*')
            ? 'doctor.treatment-plans.show'
            : 'dashboard.treatment-plans.show';

        return redirect()->route($route, $plan)
            ->with('success', __('app.treatment_plan_created'));
    }

    public function show(TreatmentPlan $plan)
    {
        $clinic = auth()->user()->clinic;
        abort_if($plan->clinic_id !== $clinic->id, 403);

        $plan->load('items.service', 'patient', 'doctor');

        return view('admin.treatment-plans.show', compact('plan'));
    }

    public function updateStatus(Request $request, TreatmentPlan $plan)
    {
        $clinic = auth()->user()->clinic;
        abort_if($plan->clinic_id !== $clinic->id, 403);

        $request->validate(['status' => 'required|string']);

        $newStatus = $request->status;
        abort_if(!$plan->canTransitionTo($newStatus), 422);

        $data = ['status' => $newStatus];

        if ($newStatus === 'presented') $data['presented_at'] = now();
        if ($newStatus === 'accepted') $data['accepted_at'] = now();
        if ($newStatus === 'completed') $data['completed_at'] = now();

        $plan->update($data);

        return back()->with('success', __('app.status_updated'));
    }

    public function completeItem(TreatmentPlan $plan, TreatmentPlanItem $item)
    {
        $clinic = auth()->user()->clinic;
        abort_if($plan->clinic_id !== $clinic->id, 403);
        abort_if($item->treatment_plan_id !== $plan->id, 404);

        $item->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Auto-complete plan if all items done
        $remaining = $plan->items()->whereNotIn('status', ['completed', 'cancelled'])->count();
        if ($remaining === 0 && $plan->status === 'in_progress') {
            $plan->update(['status' => 'completed', 'completed_at' => now()]);
        }

        return back()->with('success', __('app.item_completed'));
    }

    public function destroy(TreatmentPlan $plan)
    {
        $clinic = auth()->user()->clinic;
        abort_if($plan->clinic_id !== $clinic->id, 403);
        abort_if(!in_array($plan->status, ['draft', 'rejected']), 403);

        $patient = $plan->patient;
        $plan->delete();

        $route = auth()->user()->role === 'doctor' && request()->is('doctor/*')
            ? 'doctor.treatment-plans.index'
            : 'dashboard.patients.treatment-plans.index';

        return redirect()->route($route, $patient)
            ->with('success', __('app.treatment_plan_deleted'));
    }
}
