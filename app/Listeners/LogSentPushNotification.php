<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use NotificationChannels\WebPush\Events\NotificationSent;

class LogSentPushNotification
{
    public function handle(NotificationSent $event): void
    {
        Log::error('Push notification BERHASIL dikirim ke layanan push', [
            'endpoint' => substr($event->subscription->endpoint, 0, 80),
            'status'   => $event->report->getResponse()?->getStatusCode(),
        ]);
    }
}
