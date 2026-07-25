<?php

namespace App\Managers;

use App\Models\Album;
use App\Models\Announcement;
use App\Models\DailyVerse;
use App\Models\Devotion;
use App\Models\Prayer;
use App\Models\Schedule;
use App\Models\User;
use App\Notifications\DailyVerseNotification;
use App\Notifications\NewAnnouncementNotification;
use App\Notifications\NewDevotionSubmittedNotification;
use App\Notifications\NewPhotosUploadedNotification;
use App\Notifications\NewPrayerRequestNotification;
use App\Notifications\NewScheduleNotification;
use App\Notifications\ScheduleReminderNotification;
use Illuminate\Support\Facades\Notification;

class NotificationManager
{
    /** Send schedule reminder to all users in the schedule's own tenant. */
    public function sendScheduleReminder(Schedule $schedule, string $type = 'day'): void
    {
        $users = User::where('is_active', true)->where('tenant_id', $schedule->tenant_id)->get();
        Notification::send($users, new ScheduleReminderNotification($schedule, $type));
    }

    /** Send daily verse notification to all active users of the verse's own tenant at 07:00. */
    public function sendDailyVerse(): void
    {
        $verse = DailyVerse::getToday();
        if (!$verse) return;

        $users = User::where('is_active', true)->where('tenant_id', $verse->tenant_id)->get();
        Notification::send($users, new DailyVerseNotification($verse));
    }

    /** Send new announcement notification, scoped to the announcement's own tenant. */
    public function sendNewAnnouncement(Announcement $announcement): void
    {
        $users = User::where('is_active', true)->where('tenant_id', $announcement->tenant_id)->get();
        Notification::send($users, new NewAnnouncementNotification($announcement));
    }

    /** Send new photos uploaded notification, excluding the uploader. Scoped to the album's own tenant. */
    public function sendNewPhotosUploaded(Album $album, int $count, ?int $excludeUserId = null): void
    {
        $users = User::where('is_active', true)
            ->where('tenant_id', $album->tenant_id)
            ->when($excludeUserId, fn ($q) => $q->where('id', '!=', $excludeUserId))
            ->get();

        Notification::send($users, new NewPhotosUploadedNotification($album, $count));
    }

    /** Send new prayer request notification to admin/ICL/CTL of the prayer's own tenant. */
    public function sendNewPrayerRequest(Prayer $prayer): void
    {
        $users = User::where('is_active', true)
            ->where('tenant_id', $prayer->tenant_id)
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_ICL, User::ROLE_CTL])
            ->get();

        Notification::send($users, new NewPrayerRequestNotification($prayer));
    }

    /** Send new devotion submitted notification to admin/ICL/CTL of the devotion's own tenant. */
    public function sendNewDevotionSubmitted(Devotion $devotion): void
    {
        $users = User::where('is_active', true)
            ->where('tenant_id', $devotion->tenant_id)
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_ICL, User::ROLE_CTL])
            ->get();

        Notification::send($users, new NewDevotionSubmittedNotification($devotion));
    }

    /** Send new schedule notification to all users in the schedule's own tenant. */
    public function sendNewSchedule(Schedule $schedule): void
    {
        $users = User::where('is_active', true)->where('tenant_id', $schedule->tenant_id)->get();
        Notification::send($users, new NewScheduleNotification($schedule));
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
