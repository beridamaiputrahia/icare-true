<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use NotificationChannels\WebPush\Events\NotificationFailed;

class LogFailedPushNotification
{
    public function handle(NotificationFailed $event): void
    {
        Log::error('Push notification GAGAL terkirim', [
            'endpoint' => $event->subscription->endpoint,
            'reason'   => $event->report->getReason(),
            'status'   => $event->report->getResponse()?->getStatusCode(),
        ]);
    }
}
