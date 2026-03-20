<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class WhatsAppDeviceOffline extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => app()->getLocale() === 'ar' ? 'جهاز الواتساب مش متصل!' : 'WhatsApp Device Offline!',
            'body' => app()->getLocale() === 'ar'
                ? 'جهاز الواتساب مش شغال — تم تخطي التحقق OTP. الرجاء إعادة توصيل الجهاز.'
                : 'WhatsApp device is disconnected — OTP verification was skipped. Please reconnect the device.',
            'type' => 'whatsapp_device_offline',
            'url' => route('super.settings.index'),
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
