<?php

namespace App\Notifications;

use App\Models\RechargeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class RechargeRequested extends Notification
{
    use Queueable;

    public function __construct(public RechargeRequest $rechargeRequest) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable): array
    {
        $clinic = $this->rechargeRequest->clinic;
        $clinicName = $clinic->name_en ?: $clinic->name_ar;

        return [
            'title' => __('app.new_recharge_request'),
            'body' => __('app.recharge_request_body', [
                'clinic' => $clinicName,
                'points' => $this->rechargeRequest->points,
                'method' => __('app.payment_' . $this->rechargeRequest->payment_method),
            ]),
            'type' => 'recharge_requested',
            'recharge_request_id' => $this->rechargeRequest->id,
            'url' => route('super.clinics.show', $clinic),
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
