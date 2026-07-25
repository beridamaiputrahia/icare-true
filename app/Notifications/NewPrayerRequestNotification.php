<?php

namespace App\Notifications;

use App\Models\Prayer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewPrayerRequestNotification extends Notification
{
    use Queueable;

    public function __construct(private Prayer $prayer) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'new_prayer',
            'title'     => 'Permintaan Doa Baru',
            'message'   => "{$this->prayer->pengirim} mengajukan doa: \"{$this->prayer->judul}\"",
            'icon'      => 'fa-hands-praying',
            'color'     => 'secondary',
            'url'       => route('prayers.show', $this->prayer),
            'prayer_id' => $this->prayer->id,
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Permintaan Doa Baru')
            ->body("{$this->prayer->pengirim} mengajukan doa: \"{$this->prayer->judul}\"")
            ->data(['url' => route('prayers.show', $this->prayer)]);
    }
}
