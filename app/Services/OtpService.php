<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\WhatsAppDeviceOffline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OctopusTeam\Waapi\Facades\Waapi;

class OtpService
{
    const COOLDOWN_SECONDS = 60;
    const MAX_PER_PHONE_PER_HOUR = 5;
    const MAX_PER_IP_PER_HOUR = 10;
    const MAX_FAILED_ATTEMPTS = 5;
    const LOCKOUT_MINUTES = 30;

    /**
     * Send OTP to phone number via WhatsApp.
     */
    public function send(string $phone, string $purpose = 'verify', ?string $ip = null): array
    {
        $phone = $this->formatPhone($phone);

        // Cooldown: prevent resend within 60 seconds
        $lastOtp = DB::table('otp_codes')
            ->where('phone', $phone)
            ->where('purpose', $purpose)
            ->latest('created_at')
            ->first();

        if ($lastOtp) {
            $secondsSinceLast = (int) now()->diffInSeconds($lastOtp->created_at, absolute: true);
            if ($secondsSinceLast < self::COOLDOWN_SECONDS) {
                $remaining = self::COOLDOWN_SECONDS - $secondsSinceLast;
                return ['success' => false, 'reason' => 'cooldown', 'remaining' => $remaining];
            }
        }

        // Rate limit: max per phone per hour
        $phoneCount = DB::table('otp_codes')
            ->where('phone', $phone)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($phoneCount >= self::MAX_PER_PHONE_PER_HOUR) {
            return ['success' => false, 'reason' => 'phone_limit'];
        }

        // Rate limit: max per IP per hour
        if ($ip) {
            $ipCount = DB::table('otp_codes')
                ->where('ip_address', $ip)
                ->where('created_at', '>=', now()->subHour())
                ->count();

            if ($ipCount >= self::MAX_PER_IP_PER_HOUR) {
                return ['success' => false, 'reason' => 'ip_limit'];
            }
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save to DB
        DB::table('otp_codes')->insert([
            'phone' => $phone,
            'code' => $code,
            'purpose' => $purpose,
            'is_used' => false,
            'ip_address' => $ip,
            'failed_attempts' => 0,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Check WhatsApp device status before sending
        if (!$this->isDeviceOnline()) {
            // Device is offline - skip OTP, auto-verify, and notify super admins
            DB::table('otp_codes')->where('phone', $phone)->where('code', $code)->update(['is_used' => true]);
            $this->notifySuperAdminsDeviceOffline();
            return ['success' => true, 'cooldown' => self::COOLDOWN_SECONDS, 'skipped' => true];
        }

        // Send via WhatsApp
        $appName = config('app.name');
        $message = "رمز التحقق الخاص بك في {$appName} هو:\n";
        $message .= "🔐 *{$code}*\n";
        $message .= "صالح لمدة 5 دقائق.\n";
        $message .= "لا تشارك هذا الرمز مع أي شخص.";

        try {
            Waapi::sendMessage($phone, $message);
            return ['success' => true, 'cooldown' => self::COOLDOWN_SECONDS];
        } catch (\Exception $e) {
            return ['success' => false, 'reason' => 'send_failed'];
        }
    }

    /**
     * Verify OTP code.
     */
    public function verify(string $phone, string $code, string $purpose = 'verify'): array
    {
        $phone = $this->formatPhone($phone);

        // Check if phone is locked out due to too many failed attempts
        $recentFailed = DB::table('otp_codes')
            ->where('phone', $phone)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->where('created_at', '>=', now()->subMinutes(self::LOCKOUT_MINUTES))
            ->sum('failed_attempts');

        if ($recentFailed >= self::MAX_FAILED_ATTEMPTS) {
            return ['success' => false, 'reason' => 'locked'];
        }

        $otp = DB::table('otp_codes')
            ->where('phone', $phone)
            ->where('code', $code)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (!$otp) {
            // Increment failed attempts on the latest unused OTP
            DB::table('otp_codes')
                ->where('phone', $phone)
                ->where('purpose', $purpose)
                ->where('is_used', false)
                ->where('expires_at', '>=', now())
                ->increment('failed_attempts');

            return ['success' => false, 'reason' => 'invalid'];
        }

        // Mark as used
        DB::table('otp_codes')->where('id', $otp->id)->update(['is_used' => true]);

        return ['success' => true];
    }

    /**
     * Get remaining cooldown seconds for a phone.
     */
    public function getCooldownRemaining(string $phone, string $purpose = 'verify'): int
    {
        $phone = $this->formatPhone($phone);

        $lastOtp = DB::table('otp_codes')
            ->where('phone', $phone)
            ->where('purpose', $purpose)
            ->latest('created_at')
            ->first();

        if (!$lastOtp) {
            return 0;
        }

        $elapsed = (int) now()->diffInSeconds($lastOtp->created_at, absolute: true);
        return max(0, self::COOLDOWN_SECONDS - $elapsed);
    }

    /**
     * Format phone for Egypt.
     */
    /**
     * Check if the WhatsApp device is online.
     */
    private function isDeviceOnline(): bool
    {
        try {
            $deviceId = config('waapi.app_key');
            if (!$deviceId) {
                return false;
            }

            $result = Waapi::getDeviceStatus($deviceId);

            return $result['success'] && ($result['data']['status'] ?? '') === 'connected';
        } catch (\Exception $e) {
            Log::warning('WhatsApp device status check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Notify all super admins that WhatsApp device is offline.
     */
    private function notifySuperAdminsDeviceOffline(): void
    {
        // Throttle: only notify once per 30 minutes
        $cacheKey = 'whatsapp_device_offline_notified';
        if (cache()->has($cacheKey)) {
            return;
        }
        cache()->put($cacheKey, true, now()->addMinutes(30));

        $superAdmins = User::where('role', 'super_admin')->get();
        foreach ($superAdmins as $admin) {
            try {
                $admin->notify(new WhatsAppDeviceOffline());
            } catch (\Exception $e) {
                Log::error('Failed to notify super admin about device offline: ' . $e->getMessage());
            }
        }
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0') && \strlen($phone) === 11) {
            $phone = '2' . $phone;
        }

        return $phone;
    }
}
