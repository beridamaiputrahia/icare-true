<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use NotificationChannels\WebPush\Events\NotificationSent;

class LogSentPushNotification
{
    public function handle(NotificationSent $event): void
    {
        Log::error('Push notification BERHASIL dikirim ke layanan push', [
            'subscribable_id' => $event->subscription->subscribable_id,
            'endpoint_full'   => $event->subscription->endpoint,
            'status'          => $event->report->getResponse()?->getStatusCode(),
        ]);
    }
}
