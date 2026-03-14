<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class InvoiceUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public Invoice $invoice,
        public string $status,
    ) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => __('app.invoice_status_updated'),
            'body' => __('app.invoice_status_changed_to', [
                'status' => __('app.status_' . $this->status),
                'total' => $this->invoice->total,
            ]),
            'type' => 'invoice_updated',
            'invoice_id' => $this->invoice->id,
            'status' => $this->status,
            'url' => route('dashboard.invoices.show', $this->invoice),
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
