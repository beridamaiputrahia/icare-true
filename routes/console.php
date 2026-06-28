<?php

use Illuminate\Support\Facades\Schedule;

// ── Schedule: Daily verse at 07:00 ─────────────────────────────────────
Schedule::command('notify:daily-verse')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->runInBackground();

// ── Schedule: Schedule reminders every hour ────────────────────────────
Schedule::command('notify:schedule-reminders')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();
