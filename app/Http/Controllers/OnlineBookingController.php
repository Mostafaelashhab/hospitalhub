<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PlatformSetting;
use App\Models\User;
use App\Notifications\AppointmentCreated;
use Illuminate\Http\Request;

class OnlineBookingController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $clinic = Clinic::where('slug', $slug)
            ->where('website_enabled', true)
            ->where('website_show_booking', true)
            ->where('status', 'active')
            ->firstOrFail();

        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'nullable|string|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        $doctor = Doctor::where('id', $validated['doctor_id'])
            ->where('clinic_id', $clinic->id)
            ->where('is_active', true)
            ->firstOrFail();

        // Find or create patient
        $patient = Patient::where('clinic_id', $clinic->id)
            ->where('phone', $validated['patient_phone'])
            ->first();

        if (!$patient) {
            $freeMode = PlatformSetting::isFreeModeActive($clinic);
            $pointCost = (int) PlatformSetting::getPointPrice();

            // Check wallet balance for new patient (skip if free mode)
            if (!$freeMode && $pointCost > 0) {
                $wallet = $clinic->wallet;
                if ($wallet && !$wallet->hasEnoughBalance($pointCost)) {
                    return back()->with('booking_error', __('app.clinic_no_points'));
                }
            }

            $patient = Patient::create([
                'clinic_id' => $clinic->id,
                'branch_id' => $doctor->branch_id,
                'name' => $validated['patient_name'],
                'phone' => $validated['patient_phone'],
                'gender' => 'male',
            ]);

            // Deduct points for new patient (skip if free mode)
            if (!$freeMode && $pointCost > 0) {
                $wallet = $clinic->wallet ?? null;
                if ($wallet) {
                    $wallet->debit($pointCost, "Online booking - {$patient->name}", 'patient', $patient->id);
                }
            }
        }

        $appointment = Appointment::create([
            'clinic_id' => $clinic->id,
            'branch_id' => $doctor->branch_id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'] ?? null,
            'status' => 'scheduled',
            'notes' => $validated['notes'] ? '[Online] ' . $validated['notes'] : '[Online Booking]',
        ]);

        // Notify clinic staff
        $admins = User::where('clinic_id', $clinic->id)
            ->whereIn('role', ['admin', 'doctor', 'secretary'])
            ->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new AppointmentCreated($appointment));
            } catch (\Exception $e) {
                // Skip
            }
        }

        return back()->with('booking_success', true);
    }
}
