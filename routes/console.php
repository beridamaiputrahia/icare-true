<?php

use Illuminate\Support\Facades\Schedule;

// ── Schedule: Generate a new daily verse at 06:00, notify at 07:00 ────
Schedule::command('verse:generate-daily')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('notify:daily-verse')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->runInBackground();

// ── Schedule: Schedule reminders every hour ────────────────────────────
Schedule::command('notify:schedule-reminders')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();
