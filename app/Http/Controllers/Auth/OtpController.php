<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function __construct(private OtpService $otp) {}

    /**
     * Show phone login form.
     */
    public function showLoginForm()
    {
        return view('auth.otp-login');
    }

    /**
     * Send OTP for login.
     */
    public function sendLoginOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $phone = $request->phone;

        // Check if user exists with this phone
        $user = User::where('phone', $phone)->where('is_active', true)->first();
        if (!$user) {
            return back()->withInput()->withErrors(['phone' => __('app.phone_not_found')]);
        }

        $result = $this->otp->send($phone, 'login', $request->ip());

        if (!$result['success']) {
            $msg = match ($result['reason']) {
                'cooldown' => __('app.otp_cooldown', ['seconds' => $result['remaining']]),
                'ip_limit' => __('app.otp_rate_limit'),
                default => __('app.otp_rate_limit'),
            };
            return back()->withInput()->withErrors(['phone' => $msg]);
        }

        // If device is offline, OTP was skipped — auto-login
        if (!empty($result['skipped'])) {
            Auth::login($user, true);
            $request->session()->regenerate();
            return redirect()->intended($this->redirectPath($user));
        }

        return redirect()->route('otp.verify.form', ['phone' => $phone, 'purpose' => 'login']);
    }

    /**
     * Show OTP verification form.
     */
    public function showVerifyForm(Request $request)
    {
        $phone = $request->query('phone');
        $purpose = $request->query('purpose', 'verify');

        if (!$phone) {
            return redirect()->route('login');
        }

        // For register purpose, ensure we have registration data in session
        if ($purpose === 'register' && !session('clinic_registration')) {
            return redirect()->route('register.clinic');
        }

        $otpPurpose = $purpose === 'register' ? 'verify' : $purpose;
        $cooldown = $this->otp->getCooldownRemaining($phone, $otpPurpose);

        return view('auth.otp-verify', compact('phone', 'purpose', 'cooldown'));
    }

    /**
     * Verify OTP and login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:6',
            'purpose' => 'required|in:login,verify,register',
        ]);

        $otpPurpose = $request->purpose === 'register' ? 'verify' : $request->purpose;
        $result = $this->otp->verify($request->phone, $request->code, $otpPurpose);

        if (!$result['success']) {
            $msg = match ($result['reason']) {
                'locked' => __('app.otp_locked'),
                default => __('app.otp_invalid'),
            };
            return back()->withInput()->withErrors(['code' => $msg]);
        }

        if ($request->purpose === 'login') {
            $user = User::where('phone', $request->phone)->where('is_active', true)->first();
            if (!$user) {
                return redirect()->route('login')->withErrors(['phone' => __('app.phone_not_found')]);
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            return redirect()->intended($this->redirectPath($user));
        }

        // Store verified phone in session
        session(['phone_verified' => $request->phone]);

        // For register purpose, complete the clinic registration
        if ($request->purpose === 'register') {
            return redirect()->route('register.clinic.complete');
        }

        return redirect()->back()->with('phone_verified', true);
    }

    /**
     * Send OTP for phone verification (registration).
     */
    public function sendVerifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $result = $this->otp->send($request->phone, 'verify', $request->ip());

        if (!$result['success']) {
            $msg = match ($result['reason']) {
                'cooldown' => __('app.otp_cooldown', ['seconds' => $result['remaining']]),
                'ip_limit' => __('app.otp_rate_limit'),
                default => __('app.otp_rate_limit'),
            };
            return response()->json(['success' => false, 'message' => $msg]);
        }

        // If device offline, auto-verify the phone
        if (!empty($result['skipped'])) {
            session(['phone_verified' => $request->phone]);
            return response()->json(['success' => true, 'skipped' => true]);
        }

        return response()->json(['success' => true, 'cooldown' => $result['cooldown']]);
    }

    /**
     * Verify OTP via AJAX (for registration form).
     */
    public function verifyAjax(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        $result = $this->otp->verify($request->phone, $request->code, 'verify');

        if ($result['success']) {
            session(['phone_verified' => $request->phone]);
        }

        return response()->json([
            'success' => $result['success'],
            'reason' => $result['reason'] ?? null,
        ]);
    }

    /**
     * Resend OTP and redirect back to verify page.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'purpose' => 'required|string',
        ]);

        $otpPurpose = $request->purpose === 'register' ? 'verify' : $request->purpose;
        $this->otp->send($request->phone, $otpPurpose, $request->ip());

        return redirect()->route('otp.verify.form', [
            'phone' => $request->phone,
            'purpose' => $request->purpose,
        ]);
    }

    private function redirectPath(User $user): string
    {
        return match ($user->role) {
            'super_admin' => route('super.dashboard'),
            'doctor' => route('doctor.dashboard'),
            default => route('dashboard'),
        };
    }
}
