<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\ClinicWallet;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use App\Notifications\NewClinicRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ClinicRegistrationController extends Controller
{
    public function showForm()
    {
        $specialties = Specialty::where('is_active', true)->get();
        return view('auth.register-clinic', compact('specialties'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            // Step 1: Basic Info
            'clinic_name_en' => 'required|string|max:255',
            'clinic_name_ar' => 'required|string|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'clinic_phone' => 'required|string|max:20',
            'clinic_email' => 'nullable|email|max:255',
            'address_en' => 'nullable|string|max:500',
            'address_ar' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:50',
            // Step 2: Clinic Details
            'doctors_count' => 'nullable|integer|min:1|max:100',
            'expected_patients_monthly' => 'nullable|integer|min:1',
            'clinic_size' => 'nullable|in:small,medium,large',
            'working_hours_from' => 'nullable|string',
            'working_hours_to' => 'nullable|string',
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:sat,sun,mon,tue,wed,thu,fri',
            'schedule' => 'nullable|array',
            'schedule.*.from' => 'nullable|string',
            'schedule.*.to' => 'nullable|string',
            'has_existing_system' => 'nullable|boolean',
            'existing_system_name' => 'nullable|string|max:255',
            'referral_source' => 'nullable|in:google,social_media,friend,ad,other',
            'notes' => 'nullable|string|max:1000',
            'is_solo_doctor' => 'nullable|boolean',
            // Step 3: Admin Info
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email|max:255',
            'admin_phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $result = DB::transaction(function () use ($validated) {
            // Generate unique slug before insert
            $slug = Str::slug($validated['clinic_name_en']);
            $originalSlug = $slug;
            $counter = 1;
            while (Clinic::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $clinic = Clinic::create([
                'name_en' => $validated['clinic_name_en'],
                'name_ar' => $validated['clinic_name_ar'],
                'slug' => $slug,
                'specialty_id' => $validated['specialty_id'],
                'phone' => $validated['clinic_phone'],
                'email' => $validated['clinic_email'] ?? null,
                'address_en' => $validated['address_en'] ?? null,
                'address_ar' => $validated['address_ar'] ?? null,
                'city' => $validated['city'] ?? null,
                'tax_number' => $validated['tax_number'] ?? null,
                'doctors_count' => $validated['doctors_count'] ?? null,
                'expected_patients_monthly' => $validated['expected_patients_monthly'] ?? null,
                'clinic_size' => $validated['clinic_size'] ?? null,
                'working_hours_from' => $validated['working_hours_from'] ?? null,
                'working_hours_to' => $validated['working_hours_to'] ?? null,
                'working_days' => $validated['working_days'] ?? null,
                'working_schedule' => $validated['schedule'] ?? null,
                'has_existing_system' => $validated['has_existing_system'] ?? false,
                'existing_system_name' => $validated['existing_system_name'] ?? null,
                'referral_source' => $validated['referral_source'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            $admin = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'phone' => $validated['admin_phone'],
                'password' => Hash::make($validated['password']),
                'clinic_id' => $clinic->id,
                'role' => 'admin',
                'is_active' => true,
            ]);

            ClinicWallet::create([
                'clinic_id' => $clinic->id,
                'balance' => 0,
            ]);

            // If solo doctor, create a Doctor record from admin info
            if (!empty($validated['is_solo_doctor'])) {
                Doctor::create([
                    'clinic_id' => $clinic->id,
                    'user_id' => $admin->id,
                    'name' => $validated['admin_name'],
                    'phone' => $validated['admin_phone'],
                    'email' => $validated['admin_email'],
                    'specialty_id' => $validated['specialty_id'],
                    'consultation_fee' => 0,
                    'is_active' => true,
                ]);
            }

            $clinic->seedDefaultPermissions();

            return ['clinic' => $clinic, 'admin' => $admin];
        });

        // Notify all super admins about the new clinic
        $superAdmins = User::where('role', 'super_admin')->get();
        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(new NewClinicRegistered($result['clinic']));
        }

        Auth::login($result['admin']);

        return redirect()->route('dashboard');
    }

    public function success()
    {
        return view('auth.register-clinic-success');
    }
}
