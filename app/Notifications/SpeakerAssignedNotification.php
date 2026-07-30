<?php

namespace App\Notifications;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SpeakerAssignedNotification extends Notification
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
            'type'        => 'speaker_assigned',
            'title'       => 'Anda Ditunjuk Sebagai Pembicara',
            'message'     => "Anda menjadi pembicara pada \"{$this->schedule->nama_kegiatan}\" — {$this->schedule->formatted_date}",
            'icon'        => 'fa-person-chalkboard',
            'color'       => 'warning',
            'url'         => route('schedules.show', $this->schedule),
            'schedule_id' => $this->schedule->id,
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Anda Ditunjuk Sebagai Pembicara')
            ->body("{$this->schedule->nama_kegiatan} — {$this->schedule->formatted_date}")
            ->data(['url' => route('schedules.show', $this->schedule)]);
    }
}
