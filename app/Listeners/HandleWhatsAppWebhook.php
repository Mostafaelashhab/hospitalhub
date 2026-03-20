<?php

namespace App\Listeners;

use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Log;
use OctopusTeam\Waapi\Events\WebhookReceived;

class HandleWhatsAppWebhook
{
    public function handle(WebhookReceived $event): void
    {
        $payload = $event->data ?? [];

        $eventType = $payload['event'] ?? null;

        if ($eventType !== 'message') {
            return;
        }

        $data = $payload['data'] ?? [];

        if (empty($data['body'])) {
            return;
        }

        try {
            WhatsappMessage::create([
                'message_id' => $data['id'] ?? null,
                'instance_id' => $payload['instanceId'] ?? null,
                'from_number' => $data['from'] ?? '',
                'to_number' => $data['to'] ?? null,
                'body' => $data['body'],
                'type' => $data['type'] ?? 'chat',
                'sender_name' => $data['notifyName'] ?? null,
                'is_group' => $data['isGroupMsg'] ?? false,
                'message_at' => isset($data['timestamp']) ? \Carbon\Carbon::createFromTimestamp($data['timestamp']) : now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store WhatsApp message: ' . $e->getMessage());
        }
    }
}
