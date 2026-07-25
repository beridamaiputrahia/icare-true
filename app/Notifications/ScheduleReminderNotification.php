<?php

namespace App\Notifications;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ScheduleReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Schedule $schedule,
        private string $type = 'day' // 'day' or 'hour'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        $when = $this->type === 'day' ? 'besok' : '1 jam lagi';

        return (new WebPushMessage)
            ->title("Pengingat: {$this->schedule->nama_kegiatan}")
            ->body("Berlangsung {$when} pukul {$this->schedule->formatted_time} di {$this->schedule->lokasi}.")
            ->data(['url' => route('schedules.show', $this->schedule)]);
    }

    public function toArray(object $notifiable): array
    {
        $when = $this->type === 'day'
            ? 'besok'
            : '1 jam lagi';

        return [
            'type'       => 'schedule_reminder',
            'title'      => "Pengingat: {$this->schedule->nama_kegiatan}",
            'message'    => "Kegiatan \"{$this->schedule->nama_kegiatan}\" akan berlangsung {$when} pukul {$this->schedule->formatted_time} di {$this->schedule->lokasi}.",
            'icon'       => 'fa-calendar-check',
            'color'      => 'primary',
            'url'        => route('schedules.show', $this->schedule),
            'schedule_id'=> $this->schedule->id,
            'reminder_type' => $this->type,
        ];
    }
}
