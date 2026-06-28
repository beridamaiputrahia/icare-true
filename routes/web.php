<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommunityAnalyticsController;
use App\Http\Controllers\DailyVerseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevotionController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PrayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StatisticsController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('dashboard'));
Route::get('/offline', fn () => view('offline'))->name('offline');

// ── Authenticated ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'birthday'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',             [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',    [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile',          [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Jadwal ──────────────────────────────────────────────────────────
    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('schedules/create', [ScheduleController::class, 'create'])->name('schedules.create')->middleware('admin');
    Route::get('schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::resource('schedules', ScheduleController::class)->except(['index', 'show', 'create'])->middleware('admin');

    // ── Pengumuman ──────────────────────────────────────────────────────
    Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create')->middleware('admin');
    Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::resource('announcements', AnnouncementController::class)->except(['index', 'show', 'create'])->middleware('admin');

    // ── Anggota ─────────────────────────────────────────────────────────
    // Static routes MUST come before {member} wildcard to avoid route conflict
    Route::get('members', [MemberController::class, 'index'])->name('members.index');
    Route::get('members/create', [MemberController::class, 'create'])->name('members.create')->middleware('admin');
    Route::get('members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::patch('members/{member}/role', [MemberController::class, 'updateRole'])->name('members.role')->middleware('admin');
    Route::resource('members', MemberController::class)
        ->except(['index', 'show', 'create'])
        ->middleware('admin');

    // ── Renungan ────────────────────────────────────────────────────────
    Route::resource('devotions', DevotionController::class);
    Route::patch('devotions/{devotion}/approve', [DevotionController::class, 'approve'])
         ->name('devotions.approve')->middleware('admin');
    Route::patch('devotions/{devotion}/reject',  [DevotionController::class, 'reject'])
         ->name('devotions.reject')->middleware('admin');

    // ── Ayat Harian ─────────────────────────────────────────────────────
    Route::get('daily-verses', [DailyVerseController::class, 'index'])->name('daily-verses.index');
    Route::get('daily-verses/create', [DailyVerseController::class, 'create'])->name('daily-verses.create')->middleware('admin');
    Route::get('daily-verses/{dailyVerse}', [DailyVerseController::class, 'show'])->name('daily-verses.show');
    Route::resource('daily-verses', DailyVerseController::class)->except(['index', 'show', 'create'])->middleware('admin');

    // ── Doa ─────────────────────────────────────────────────────────────
    Route::resource('prayers', PrayerController::class);
    Route::patch('prayers/{prayer}/approve',  [PrayerController::class, 'approve'])
         ->name('prayers.approve')->middleware('admin');
    Route::patch('prayers/{prayer}/answered', [PrayerController::class, 'markAnswered'])
         ->name('prayers.answered')->middleware('admin');

    // ── Achievement ─────────────────────────────────────────────────────
    Route::get('achievements', [AchievementController::class, 'index'])->name('achievements.index');
    Route::get('achievements/user/{user}', [AchievementController::class, 'userDetail'])
         ->name('achievements.user');

    // ── Statistik ───────────────────────────────────────────────────────
    Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');

    // ── Notifikasi ──────────────────────────────────────────────────────
    Route::get('notifications',          [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/read-all',[NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('notifications/{id}/read',[NotificationController::class, 'markRead'])->name('notifications.read');
    Route::delete('notifications/{id}',  [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('notifications/count',    [NotificationController::class, 'unreadCount'])->name('notifications.count');

    // ── Export (PDF) ─────────────────────────────────────────────────────
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('schedule/{schedule}/pdf',    [ExportController::class, 'schedulePdf'])->name('schedule.pdf');
        Route::get('schedules/pdf',              [ExportController::class, 'schedulesListPdf'])->name('schedules.pdf');
        Route::get('announcement/{announcement}/pdf', [ExportController::class, 'announcementPdf'])->name('announcement.pdf');
        Route::get('devotion/{devotion}/pdf',    [ExportController::class, 'devotionPdf'])->name('devotion.pdf');
        Route::get('statistics/pdf',             [ExportController::class, 'statisticsPdf'])->name('statistics.pdf')->middleware('admin');
    });

    // ── Pengaturan (Admin only) ──────────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::get('settings',                    [AppSettingController::class, 'index'])->name('settings.index');
        Route::put('settings',                    [AppSettingController::class, 'update'])->name('settings.update');
        Route::post('settings/maintenance-toggle',[AppSettingController::class, 'maintenanceToggle'])
             ->name('settings.maintenance-toggle');
    });

    // ── Phase 4: QR Code ─────────────────────────────────────────────────
    Route::prefix('qr')->name('qr.')->group(function () {
        Route::get('members/{member}',          [QrCodeController::class, 'show'])->name('member');
        Route::get('members/{member}/download', [QrCodeController::class, 'download'])->name('download');
        Route::get('members/{member}/svg',      [QrCodeController::class, 'svg'])->name('svg');
    });

    // ── Phase 4: Leaderboard ─────────────────────────────────────────────
    Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    // ── Phase 4: Points ──────────────────────────────────────────────────
    Route::get('my-points', [PointController::class, 'index'])->name('points.index');

    // ── Phase 4: Gallery ─────────────────────────────────────────────────
    Route::resource('albums', AlbumController::class);
    Route::prefix('albums/{album}')->name('photos.')->group(function () {
        Route::post('photos',           [PhotoController::class, 'store'])->name('store');
        Route::delete('photos/{photo}', [PhotoController::class, 'destroy'])->name('destroy');
        Route::get('photos/{photo}/download', [PhotoController::class, 'download'])->name('download');
        Route::patch('photos/{photo}/caption', [PhotoController::class, 'updateCaption'])->name('caption');
    });

    // ── Phase 4: Chat ─────────────────────────────────────────────────────
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/',                  [ChatController::class, 'index'])->name('index');
        Route::post('send',              [ChatController::class, 'send'])->name('send');
        Route::post('typing',            [ChatController::class, 'typing'])->name('typing');
        Route::post('messages/{message}/delete', [ChatController::class, 'deleteMessage'])->name('delete');
        Route::get('load-more',          [ChatController::class, 'loadMore'])->name('load-more');
        Route::get('start/{user}',       [ChatController::class, 'startPrivate'])->name('start-private');
    });

    // ── Phase 4: Community Analytics (admin) ────────────────────────────
    Route::get('analytics', [CommunityAnalyticsController::class, 'index'])
         ->name('analytics.index')->middleware('admin');
});

require __DIR__ . '/auth.php';
