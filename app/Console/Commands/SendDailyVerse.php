<?php

namespace App\Console\Commands;

use App\Managers\NotificationManager;
use Illuminate\Console\Command;

class SendDailyVerse extends Command
{
    protected $signature   = 'notify:daily-verse';
    protected $description = 'Send daily verse notification to all active users (dijadwalkan 3x sehari: 06:05, 12:00, 19:00)';

    public function handle(NotificationManager $manager): void
    {
        $manager->sendDailyVerse();
        $this->info('Daily verse notification sent.');
    }
}
