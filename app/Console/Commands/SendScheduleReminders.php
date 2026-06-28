<?php

namespace App\Console\Commands;

use App\Managers\NotificationManager;
use App\Models\Schedule;
use Illuminate\Console\Command;

class SendScheduleReminders extends Command
{
    protected $signature   = 'notify:schedule-reminders';
    protected $description = 'Send H-1 day and H-1 hour schedule reminders';

    public function handle(NotificationManager $manager): void
    {
        $tomorrow = now()->addDay()->toDateString();
        $hourAhead = now()->addHour();

        // H-1 Day: schedules happening tomorrow
        $daySchedules = Schedule::where('status', 'upcoming')
            ->whereDate('tanggal', $tomorrow)
            ->get();

        foreach ($daySchedules as $schedule) {
            $manager->sendScheduleReminder($schedule, 'day');
            $this->info("Day reminder sent: {$schedule->nama_kegiatan}");
        }

        // H-1 Hour: schedules happening within the next hour (±5 min tolerance)
        $hourSchedules = Schedule::where('status', 'upcoming')
            ->whereDate('tanggal', today()->toDateString())
            ->whereTime('jam', '>=', $hourAhead->format('H:i:00'))
            ->whereTime('jam', '<=', $hourAhead->addMinutes(5)->format('H:i:59'))
            ->get();

        foreach ($hourSchedules as $schedule) {
            $manager->sendScheduleReminder($schedule, 'hour');
            $this->info("Hour reminder sent: {$schedule->nama_kegiatan}");
        }
    }
}
