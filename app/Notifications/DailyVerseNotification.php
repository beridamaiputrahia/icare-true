<?php

namespace App\Notifications;

use App\Models\DailyVerse;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DailyVerseNotification extends Notification
{
    use Queueable;

    public function __construct(private DailyVerse $verse) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Ayat Harian Hari Ini')
            ->body("{$this->verse->ayat} — {$this->verse->referensi}")
            ->data(['url' => route('daily-verses.show', $this->verse)]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'daily_verse',
            'title'      => 'Ayat Harian Hari Ini',
            'message'    => "\"{$this->verse->ayat}\" — {$this->verse->referensi}",
            'icon'       => 'fa-bible',
            'color'      => 'warning',
            'url'        => route('daily-verses.show', $this->verse),
            'verse_id'   => $this->verse->id,
        ];
    }
}
