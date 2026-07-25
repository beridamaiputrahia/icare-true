<?php

namespace App\Notifications;

use App\Models\Album;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewPhotosUploadedNotification extends Notification
{
    use Queueable;

    public function __construct(private Album $album, private int $count) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'     => 'new_photos',
            'title'    => 'Foto Baru di Galeri',
            'message'  => "{$this->count} foto baru diunggah ke album \"{$this->album->judul}\".",
            'icon'     => 'fa-images',
            'color'    => 'success',
            'url'      => route('albums.show', $this->album),
            'album_id' => $this->album->id,
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Foto Baru di Galeri')
            ->body("{$this->count} foto baru diunggah ke album \"{$this->album->judul}\".")
            ->data(['url' => route('albums.show', $this->album)]);
    }
}
