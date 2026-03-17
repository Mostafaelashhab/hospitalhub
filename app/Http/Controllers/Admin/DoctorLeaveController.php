<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use Illuminate\Http\Request;

class DoctorLeaveController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $query = DoctorLeave::where('clinic_id', $clinic->id)
            ->with('doctor')
            ->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $leaves = $query->paginate(15);

        return view('admin.leaves.index', compact('leaves'));
    }

    public function create()
    {
        $clinic = auth()->user()->clinic;
        $doctors = Doctor::where('clinic_id', $clinic->id)->where('is_active', true)->get();

        return view('admin.leaves.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $validated = $request->validate([
            'doctor_id'           => 'required|exists:doctors,id',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'reason'              => 'required|string|max:1000',
            'cancel_appointments' => 'nullable|boolean',
        ]);

        $cancelAppointments = $request->boolean('cancel_appointments');

        $leave = DoctorLeave::create([
            'clinic_id'           => $clinic->id,
            'doctor_id'           => $validated['doctor_id'],
            'start_date'          => $validated['start_date'],
            'end_date'            => $validated['end_date'],
            'reason'              => $validated['reason'],
            'status'              => 'pending',
            'cancel_appointments' => $cancelAppointments,
        ]);

        if ($cancelAppointments) {
            $this->cancelAffectedAppointments($leave);
        }

        return redirect()->route('dashboard.leaves.index')
            ->with('success', __('app.leave_created'));
    }

    public function approve(DoctorLeave $leave)
    {
        $clinic = auth()->user()->clinic;
        abort_if($leave->clinic_id !== $clinic->id, 403);

        $leave->update(['status' => 'approved']);

        if ($leave->cancel_appointments) {
            $this->cancelAffectedAppointments($leave);
        }

        return back()->with('success', __('app.leave_approved'));
    }

    public function reject(DoctorLeave $leave)
    {
        $clinic = auth()->user()->clinic;
        abort_if($leave->clinic_id !== $clinic->id, 403);

        $leave->update(['status' => 'rejected']);

        return back()->with('success', __('app.leave_rejected'));
    }

    public function destroy(DoctorLeave $leave)
    {
        $clinic = auth()->user()->clinic;
        abort_if($leave->clinic_id !== $clinic->id, 403);

        $leave->delete();

        return back()->with('success', __('app.leave_deleted'));
    }

    private function cancelAffectedAppointments(DoctorLeave $leave): void
    {
        Appointment::where('doctor_id', $leave->doctor_id)
            ->whereBetween('appointment_date', [
                $leave->start_date->format('Y-m-d'),
                $leave->end_date->format('Y-m-d'),
            ])
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->update(['status' => 'cancelled']);
    }
}
