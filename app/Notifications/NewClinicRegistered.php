<?php

namespace App\Notifications;

use App\Models\Clinic;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewClinicRegistered extends Notification
{
    use Queueable;

    public function __construct(public Clinic $clinic) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable): array
    {
        $clinicName = app()->getLocale() === 'ar' ? $this->clinic->name_ar : $this->clinic->name_en;

        return [
            'title' => __('app.new_clinic_registered'),
            'body' => __('app.clinic_registered_message', ['name' => $clinicName]),
            'type' => 'new_clinic_registered',
            'clinic_id' => $this->clinic->id,
            'url' => route('super.clinics.show', $this->clinic),
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
