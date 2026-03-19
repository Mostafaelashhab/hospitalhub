<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use OctopusTeam\Waapi\Facades\Waapi;

class OtpService
{
    /**
     * Send OTP to phone number via WhatsApp.
     */
    public function send(string $phone, string $purpose = 'verify'): bool
    {
        $phone = $this->formatPhone($phone);

        // Rate limit: max 5 OTPs per phone per hour
        $recentCount = DB::table('otp_codes')
            ->where('phone', $phone)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($recentCount >= 5) {
            return false;
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save to DB
        DB::table('otp_codes')->insert([
            'phone' => $phone,
            'code' => $code,
            'purpose' => $purpose,
            'is_used' => false,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send via WhatsApp
        $appName = config('app.name');
        $message = "رمز التحقق الخاص بك في {$appName} هو:\n\n";
        $message .= "🔐 *{$code}*\n\n";
        $message .= "صالح لمدة 5 دقائق.\n";
        $message .= "لا تشارك هذا الرمز مع أي شخص.";

        try {
            Waapi::sendMessage($phone, $message);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verify OTP code.
     */
    public function verify(string $phone, string $code, string $purpose = 'verify'): bool
    {
        $phone = $this->formatPhone($phone);

        $otp = DB::table('otp_codes')
            ->where('phone', $phone)
            ->where('code', $code)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (!$otp) {
            return false;
        }

        // Mark as used
        DB::table('otp_codes')->where('id', $otp->id)->update(['is_used' => true]);

        return true;
    }

    /**
     * Format phone for Egypt.
     */
    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0') && \strlen($phone) === 11) {
            $phone = '2' . $phone;
        }

        return $phone;
    }
}
