<?php

namespace App\Notifications;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewScheduleNotification extends Notification
{
    use Queueable;

    public function __construct(private Schedule $schedule) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'new_schedule',
            'title'       => 'Jadwal Baru',
            'message'     => "{$this->schedule->nama_kegiatan} — {$this->schedule->lokasi}",
            'icon'        => 'fa-calendar-plus',
            'color'       => 'primary',
            'url'         => route('schedules.show', $this->schedule),
            'schedule_id' => $this->schedule->id,
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Jadwal Baru')
            ->body("{$this->schedule->nama_kegiatan} — {$this->schedule->lokasi}")
            ->data(['url' => route('schedules.show', $this->schedule)]);
    }
}
