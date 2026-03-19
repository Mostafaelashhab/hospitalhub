<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send WhatsApp reminders for tomorrow appointments';

    public function handle(WhatsAppService $whatsApp): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $appointments = Appointment::with(['patient', 'doctor'])
            ->where('appointment_date', $tomorrow)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->whereHas('patient', fn($q) => $q->whereNotNull('phone'))
            ->get();

        $sent = 0;

        foreach ($appointments as $appointment) {
            try {
                $isFollowUp = str_contains($appointment->notes ?? '', 'Follow-up');

                if ($isFollowUp) {
                    $whatsApp->notifyFollowUpReminder($appointment);
                } else {
                    $whatsApp->notifyAppointmentReminder($appointment);
                }

                $sent++;
            } catch (\Exception $e) {
                $this->error("Failed for appointment #{$appointment->id}: {$e->getMessage()}");
            }
        }

        $this->info("Sent {$sent} reminders for {$appointments->count()} appointments.");

        return self::SUCCESS;
    }
}
