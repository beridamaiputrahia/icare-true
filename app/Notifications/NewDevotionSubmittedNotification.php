<?php

namespace App\Notifications;

use App\Models\Devotion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewDevotionSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(private Devotion $devotion) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'new_devotion',
            'title'       => 'Renungan Baru Menunggu Persetujuan',
            'message'     => "{$this->devotion->user->name} mengirim renungan: \"{$this->devotion->judul}\"",
            'icon'        => 'fa-book-open',
            'color'       => 'warning',
            'url'         => route('devotions.show', $this->devotion),
            'devotion_id' => $this->devotion->id,
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Renungan Baru Menunggu Persetujuan')
            ->body("{$this->devotion->user->name} mengirim renungan: \"{$this->devotion->judul}\"")
            ->data(['url' => route('devotions.show', $this->devotion)]);
    }
}
