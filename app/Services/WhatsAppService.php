<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;
use OctopusTeam\Waapi\Facades\Waapi;

class WhatsAppService
{
    /**
     * Format phone number for Egypt.
     */
    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0') && \strlen($phone) === 11) {
            $phone = '2' . $phone;
        }

        return $phone;
    }

    /**
     * Send a WhatsApp message (with logging).
     */
    public function send(string $phone, string $message): array
    {
        $phone = $this->formatPhone($phone);

        try {
            $result = Waapi::sendMessage($phone, $message);

            if ($result['success'] ?? false) {
                Log::info('WhatsApp sent', ['phone' => $phone]);
            } else {
                Log::error('WhatsApp failed', ['phone' => $phone, 'result' => $result]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('WhatsApp exception', ['phone' => $phone, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Notify patient about new appointment booking.
     */
    public function notifyAppointmentBooked(Appointment $appointment): void
    {
        $appointment->loadMissing(['patient', 'doctor']);
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        if (!$patient?->phone) return;

        $date = $appointment->appointment_date->format('Y-m-d');
        $time = $appointment->appointment_time;

        $message = "مرحباً {$patient->name} 👋\n\n";
        $message .= "تم حجز موعدك بنجاح ✅\n\n";
        $message .= "🩺 الدكتور: {$doctor->name}\n";
        $message .= "📅 التاريخ: {$date}\n";
        $message .= "🕐 الوقت: {$time}\n\n";
        $message .= "برجاء الحضور قبل الموعد بـ 10 دقائق.\n";
        $message .= "لإلغاء أو تعديل الموعد، تواصل مع العيادة.";

        $this->send($patient->phone, $message);

        // Notify doctor
        $this->notifyDoctor($doctor, "📋 تم حجز موعد جديد\n👤 المريض: {$patient->name}\n📅 {$date} - {$time}");
    }

    /**
     * Notify patient about appointment reminder (day before).
     */
    public function notifyAppointmentReminder(Appointment $appointment): void
    {
        $appointment->loadMissing(['patient', 'doctor']);
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        if (!$patient?->phone) return;

        $date = $appointment->appointment_date->format('Y-m-d');
        $time = $appointment->appointment_time;

        $message = "تذكير 🔔\n\n";
        $message .= "مرحباً {$patient->name}\n";
        $message .= "موعدك بكرة مع د. {$doctor->name}\n";
        $message .= "📅 {$date}\n";
        $message .= "🕐 {$time}\n\n";
        $message .= "منتظرينك! 😊";

        $this->send($patient->phone, $message);
    }

    /**
     * Notify patient about diagnosis details (prescription, labs, radiology).
     */
    public function notifyDiagnosisCreated(Diagnosis $diagnosis): void
    {
        $diagnosis->loadMissing(['patient', 'doctor', 'prescription.items']);
        $patient = $diagnosis->patient;
        $doctor = $diagnosis->doctor;

        if (!$patient?->phone) return;

        $message = "مرحباً {$patient->name} 👋\n\n";
        $message .= "تم تسجيل التشخيص من د. {$doctor->name}\n\n";

        // Prescription / Medications
        if ($diagnosis->prescription && $diagnosis->prescription->items->isNotEmpty()) {
            $message .= "💊 *الأدوية:*\n";
            foreach ($diagnosis->prescription->items as $i => $item) {
                $num = $i + 1;
                $message .= "{$num}. {$item->drug_name}";
                if ($item->dosage) $message .= " - {$item->dosage}";
                if ($item->frequency) $message .= " - {$item->frequency}";
                if ($item->duration) $message .= " ({$item->duration})";
                $message .= "\n";
                if ($item->instructions) $message .= "   ℹ️ {$item->instructions}\n";
            }
            $message .= "\n";
        }

        // Lab tests
        if ($diagnosis->lab_tests) {
            $message .= "🧪 *التحاليل المطلوبة:*\n{$diagnosis->lab_tests}\n\n";
        }

        // Radiology
        if ($diagnosis->radiology) {
            $message .= "📷 *الأشعة المطلوبة:*\n{$diagnosis->radiology}\n\n";
        }

        $message .= "سلامتك! 🙏";

        $this->send($patient->phone, $message);

        // Notify doctor that messages were sent
        $sentItems = [];
        if ($diagnosis->prescription && $diagnosis->prescription->items->isNotEmpty()) $sentItems[] = 'الأدوية';
        if ($diagnosis->lab_tests) $sentItems[] = 'التحاليل';
        if ($diagnosis->radiology) $sentItems[] = 'الأشعة';

        if (!empty($sentItems)) {
            $this->notifyDoctor($doctor, "✅ تم إرسال (" . implode('، ', $sentItems) . ") للمريض {$patient->name} على الواتساب.");
        }
    }

    /**
     * Notify patient about follow-up appointment.
     */
    public function notifyFollowUpCreated(Appointment $followUp, Appointment $originalAppointment): void
    {
        $followUp->loadMissing(['patient', 'doctor']);
        $patient = $followUp->patient;
        $doctor = $followUp->doctor;

        if (!$patient?->phone) return;

        $date = $followUp->appointment_date->format('Y-m-d');
        $time = $followUp->appointment_time;

        $message = "مرحباً {$patient->name} 👋\n\n";
        $message .= "تم تحديد موعد إعادة لك 🔄\n\n";
        $message .= "🩺 د. {$doctor->name}\n";
        $message .= "📅 {$date}\n";
        $message .= "🕐 {$time}\n\n";
        $message .= "منتظرينك! 😊";

        $this->send($patient->phone, $message);

        $this->notifyDoctor($doctor, "🔄 تم إرسال تذكير إعادة للمريض {$patient->name}\n📅 {$date}");
    }

    /**
     * Notify patient about follow-up reminder (day before).
     */
    public function notifyFollowUpReminder(Appointment $appointment): void
    {
        $appointment->loadMissing(['patient', 'doctor']);
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        if (!$patient?->phone) return;

        $date = $appointment->appointment_date->format('Y-m-d');
        $time = $appointment->appointment_time;

        $message = "تذكير بموعد الإعادة 🔔\n\n";
        $message .= "مرحباً {$patient->name}\n";
        $message .= "موعد إعادتك بكرة مع د. {$doctor->name}\n";
        $message .= "📅 {$date}\n";
        $message .= "🕐 {$time}\n\n";
        $message .= "منتظرينك! 😊";

        $this->send($patient->phone, $message);
    }

    /**
     * Send a notification to the doctor's user account via WhatsApp.
     */
    private function notifyDoctor(Doctor $doctor, string $message): void
    {
        $doctor->loadMissing('user');
        $phone = $doctor->user?->phone;

        if (!$phone) return;

        $this->send($phone, $message);
    }
}
