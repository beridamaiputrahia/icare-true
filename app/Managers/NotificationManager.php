<?php

namespace App\Managers;

use App\Models\Announcement;
use App\Models\DailyVerse;
use App\Models\Schedule;
use App\Models\User;
use App\Notifications\DailyVerseNotification;
use App\Notifications\NewAnnouncementNotification;
use App\Notifications\ScheduleReminderNotification;
use Illuminate\Support\Facades\Notification;

class NotificationManager
{
    /** Send schedule reminder to all users. */
    public function sendScheduleReminder(Schedule $schedule, string $type = 'day'): void
    {
        $users = User::where('is_active', true)->get();
        Notification::send($users, new ScheduleReminderNotification($schedule, $type));
    }

    /** Send daily verse notification to all active users at 07:00. */
    public function sendDailyVerse(): void
    {
        $verse = DailyVerse::getToday();
        if (!$verse) return;

        $users = User::where('is_active', true)->get();
        Notification::send($users, new DailyVerseNotification($verse));
    }

    /** Send new announcement notification. */
    public function sendNewAnnouncement(Announcement $announcement): void
    {
        $users = User::where('is_active', true)->get();
        Notification::send($users, new NewAnnouncementNotification($announcement));
    }

    /** Get unread count for user. */
    public function unreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    /** Get latest N notifications for user. */
    public function getForUser(User $user, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $user->notifications()->latest()->take($limit)->get();
    }

    /** Mark all as read for user. */
    public function markAllRead(User $user): void
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }
}
