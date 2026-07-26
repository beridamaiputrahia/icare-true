<?php

use Illuminate\Support\Facades\Schedule;

// ── Schedule: Generate a new daily verse at 06:00 ──────────────────────
Schedule::command('verse:generate-daily')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->runInBackground();

// ── Schedule: Notify daily verse 3x sehari — pagi, siang, malam ────────
// 06:05 (bukan 06:00 persis) supaya verse:generate-daily di atas sudah
// selesai membuat ayat hari ini sebelum notifikasi pertama dikirim.
Schedule::command('notify:daily-verse')
    ->dailyAt('06:05')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('notify:daily-verse')
    ->dailyAt('12:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('notify:daily-verse')
    ->dailyAt('19:00')
    ->withoutOverlapping()
    ->runInBackground();

// ── Schedule: Schedule reminders every hour ────────────────────────────
Schedule::command('notify:schedule-reminders')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();
