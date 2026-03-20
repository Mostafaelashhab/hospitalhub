<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\ClinicWallet;
use App\Models\Specialty;
use App\Models\User;
use App\Notifications\NewClinicRegistered;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ClinicRegistrationController extends Controller
{
    public function __construct(private OtpService $otp) {}

    public function showForm()
    {
        $specialties = Specialty::where('is_active', true)->get();
        return view('auth.register-clinic', compact('specialties'));
    }

    /**
     * Step 1: Validate form, save to session, send OTP, redirect to verify page.
     */
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
            'has_existing_system' => 'nullable|boolean',
            'existing_system_name' => 'nullable|string|max:255',
            'referral_source' => 'nullable|in:google,social_media,friend,ad,other',
            'notes' => 'nullable|string|max:1000',
            // Step 3: Admin Info
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email|max:255',
            'admin_phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Save form data to session
        session(['clinic_registration' => $validated]);

        // Send OTP to admin phone
        $result = $this->otp->send($validated['admin_phone'], 'verify', $request->ip());

        if (!$result['success'] && $result['reason'] !== 'cooldown') {
            return back()->withInput()->withErrors(['admin_phone' => __('app.otp_rate_limit')]);
        }

        // If device is offline, OTP was skipped — complete registration directly
        if (!empty($result['skipped'])) {
            session(['phone_verified' => $validated['admin_phone']]);
            return redirect()->route('register.clinic.complete');
        }

        // Redirect to OTP verification page
        return redirect()->route('otp.verify.form', [
            'phone' => $validated['admin_phone'],
            'purpose' => 'register',
        ]);
    }

    /**
     * Step 2: After OTP verified, complete the registration.
     */
    public function completeRegistration(Request $request)
    {
        $validated = session('clinic_registration');

        if (!$validated) {
            return redirect()->route('register.clinic');
        }

        // Verify phone was confirmed via OTP
        if (!session('phone_verified') || session('phone_verified') !== $validated['admin_phone']) {
            return redirect()->route('register.clinic')
                ->withInput($validated)
                ->withErrors(['admin_phone' => __('app.phone_not_verified')]);
        }

        // Check email uniqueness again (in case someone registered in the meantime)
        if (User::where('email', $validated['admin_email'])->exists()) {
            session()->forget(['clinic_registration', 'phone_verified']);
            return redirect()->route('register.clinic')
                ->withInput($validated)
                ->withErrors(['admin_email' => __('validation.unique', ['attribute' => __('app.admin_email')])]);
        }

        $result = DB::transaction(function () use ($validated) {
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

            $clinic->seedDefaultPermissions();
            \App\Models\ClinicRole::seedForClinic($clinic->id);

            return ['clinic' => $clinic, 'admin' => $admin];
        });

        // Notify super admins
        $superAdmins = User::where('role', 'super_admin')->get();
        foreach ($superAdmins as $superAdmin) {
            try {
                $superAdmin->notify(new NewClinicRegistered($result['clinic']));
            } catch (\Exception $e) {
                // Skip notification failures
            }
        }

        // Clean up session
        session()->forget(['clinic_registration', 'phone_verified']);

        Auth::login($result['admin']);

        return redirect()->route('dashboard');
    }

    public function success()
    {
        return view('auth.register-clinic-success');
    }
}
