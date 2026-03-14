<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AppointmentCreated extends Notification
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
            'title' => __('app.new_appointment'),
            'body' => __('app.appointment_scheduled_at', [
                'date' => $this->appointment->appointment_date,
                'time' => $this->appointment->appointment_time,
            ]),
            'type' => 'appointment_created',
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
