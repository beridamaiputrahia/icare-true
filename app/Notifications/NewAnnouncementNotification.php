<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewAnnouncementNotification extends Notification
{
    use Queueable;

    public function __construct(private Announcement $announcement) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Pengumuman Baru')
            ->body($this->announcement->judul)
            ->data(['url' => route('announcements.show', $this->announcement)]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'            => 'new_announcement',
            'title'           => 'Pengumuman Baru',
            'message'         => $this->announcement->judul,
            'icon'            => 'fa-bullhorn',
            'color'           => 'info',
            'url'             => route('announcements.show', $this->announcement),
            'announcement_id' => $this->announcement->id,
        ];
    }
}
