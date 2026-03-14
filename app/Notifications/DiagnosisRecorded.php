<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DiagnosisRecorded extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => __('app.diagnosis_recorded'),
            'body' => __('app.diagnosis_recorded_for_appointment'),
            'type' => 'diagnosis_recorded',
            'appointment_id' => $this->appointment->id,
            'url' => route('dashboard.appointments.show', $this->appointment),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $data = $this->toArray($notifiable);

        return (new WebPushMessage)
            ->title($data['title'])
            ->icon('/icons/icon-192x192.png')
            ->badge('/icons/icon-96x96.png')
            ->body($data['body'])
            ->data(['url' => $data['url']]);
    }
}
