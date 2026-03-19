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

        if (!$this->otp->send($phone, 'login')) {
            return back()->withInput()->withErrors(['phone' => __('app.otp_rate_limit')]);
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

        return view('auth.otp-verify', compact('phone', 'purpose'));
    }

    /**
     * Verify OTP and login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:6',
            'purpose' => 'required|in:login,verify',
        ]);

        if (!$this->otp->verify($request->phone, $request->code, $request->purpose)) {
            return back()->withInput()->withErrors(['code' => __('app.otp_invalid')]);
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

        // For verify purpose (registration), store verified phone in session
        session(['phone_verified' => $request->phone]);

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

        if (!$this->otp->send($request->phone, 'verify')) {
            return response()->json(['success' => false, 'message' => __('app.otp_rate_limit')]);
        }

        return response()->json(['success' => true]);
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

        $valid = $this->otp->verify($request->phone, $request->code, 'verify');

        if ($valid) {
            session(['phone_verified' => $request->phone]);
        }

        return response()->json(['success' => $valid]);
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
