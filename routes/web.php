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
use App\Http\Controllers\GameController;
use App\Http\Controllers\StatisticsController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('dashboard'));
Route::get('/offline', fn () => view('offline'))->name('offline');
Route::get('/manifest.json', function (\Illuminate\Http\Request $request) {
    // Identifikasi tenant: dari user login (jika ada) atau dari subdomain
    $tenantId = null;
    if (auth()->check()) {
        $tenantId = auth()->user()->tenant_id;
    } else {
        // Coba deteksi dari subdomain: {slug}.domain.com
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        $tenant = \App\Models\Tenant::where('slug', $subdomain)->where('is_active', true)->first();
        $tenantId = $tenant?->id;
    }

    $logo    = \App\Models\AppSetting::get('logo', null, $tenantId);
    $appName = \App\Models\AppSetting::get('app_name', 'I Care True', $tenantId);
    $iconUrl = $logo ? \Illuminate\Support\Facades\Storage::url($logo) : '/icons/icon.svg';

    $manifest = [
        'name'             => $appName,
        'short_name'       => $appName,
        'description'      => 'Pusat Informasi Komunitas Rohani Kristen',
        'start_url'        => '/',
        'scope'            => '/',
        'display'          => 'standalone',
        'orientation'      => 'portrait',
        'background_color' => '#1e293b',
        'theme_color'      => '#2563eb',
        'categories'       => ['lifestyle', 'social'],
        'lang'             => 'id',
        'icons'            => [
            ['src' => $iconUrl, 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable'],
            ['src' => $iconUrl, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
        ],
        'shortcuts' => [
            ['name' => 'Dashboard', 'url' => '/dashboard', 'icons' => [['src' => $iconUrl, 'sizes' => '96x96']]],
            ['name' => 'Jadwal',    'url' => '/schedules', 'icons' => [['src' => $iconUrl, 'sizes' => '96x96']]],
            ['name' => 'Doa',       'url' => '/prayers',   'icons' => [['src' => $iconUrl, 'sizes' => '96x96']]],
        ],
    ];
    return response()->json($manifest)->header('Content-Type', 'application/manifest+json');
})->name('manifest');
Route::get('game/assets/feature-js', [\App\Http\Controllers\GameController::class, 'serveJsx'])->name('game.jsx');

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
    Route::get('schedules/create', [ScheduleController::class, 'create'])->name('schedules.create')
         ->middleware(['role:admin,icl,ctl', 'feature:jadwal']);
    Route::get('schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::resource('schedules', ScheduleController::class)->except(['index', 'show', 'create'])
         ->middleware(['role:admin,icl,ctl', 'feature:jadwal']);

    // ── Pengumuman ──────────────────────────────────────────────────────
    Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create')
         ->middleware(['role:admin,icl,ctl', 'feature:pengumuman']);
    Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::resource('announcements', AnnouncementController::class)->except(['index', 'show', 'create'])
         ->middleware(['role:admin,icl,ctl', 'feature:pengumuman']);

    // ── Anggota ─────────────────────────────────────────────────────────
    // Static routes MUST come before {member} wildcard to avoid route conflict
    Route::get('members', [MemberController::class, 'index'])->name('members.index');
    Route::get('members/create', [MemberController::class, 'create'])->name('members.create')
         ->middleware(['role:admin,icl,ctl', 'feature:anggota']);
    Route::get('members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::patch('members/{member}/role', [MemberController::class, 'updateRole'])->name('members.role')
         ->middleware('admin');
    Route::patch('members/{member}/secondary-role', [MemberController::class, 'updateSecondaryRole'])
         ->name('members.secondary-role')->middleware('admin');
    Route::resource('members', MemberController::class)
        ->except(['index', 'show', 'create'])
        ->middleware(['role:admin,icl,ctl', 'feature:anggota']);

    // ── Renungan ────────────────────────────────────────────────────────
    Route::resource('devotions', DevotionController::class);
    Route::patch('devotions/{devotion}/approve', [DevotionController::class, 'approve'])
         ->name('devotions.approve')->middleware(['role:admin,icl,ctl', 'feature:renungan']);
    Route::patch('devotions/{devotion}/reject',  [DevotionController::class, 'reject'])
         ->name('devotions.reject')->middleware(['role:admin,icl,ctl', 'feature:renungan']);

    // ── Ayat Harian ─────────────────────────────────────────────────────
    Route::get('daily-verses', [DailyVerseController::class, 'index'])->name('daily-verses.index');
    Route::get('daily-verses/create', [DailyVerseController::class, 'create'])->name('daily-verses.create')
         ->middleware(['role:admin,icl,ctl', 'feature:ayat_harian']);
    Route::get('daily-verses/{dailyVerse}', [DailyVerseController::class, 'show'])->name('daily-verses.show');
    Route::resource('daily-verses', DailyVerseController::class)->except(['index', 'show', 'create'])
         ->middleware(['role:admin,icl,ctl', 'feature:ayat_harian']);

    // ── Doa ─────────────────────────────────────────────────────────────
    Route::resource('prayers', PrayerController::class);
    Route::patch('prayers/{prayer}/approve',  [PrayerController::class, 'approve'])
         ->name('prayers.approve')->middleware(['role:admin,icl,ctl', 'feature:doa']);
    Route::patch('prayers/{prayer}/answered', [PrayerController::class, 'markAnswered'])
         ->name('prayers.answered')->middleware(['role:admin,icl,ctl', 'feature:doa']);

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

    // ── Game Hub ─────────────────────────────────────────────────────────
    Route::get('game', [GameController::class, 'index'])->name('game.index');
    Route::post('game/challenge',     [\App\Http\Controllers\GameSessionController::class, 'challenge'])->name('game.challenge');
    Route::post('game/respond',       [\App\Http\Controllers\GameSessionController::class, 'respond'])->name('game.respond');
    Route::post('game/move',          [\App\Http\Controllers\GameSessionController::class, 'move'])->name('game.move');
    Route::get('game/pending',        [\App\Http\Controllers\GameSessionController::class, 'pending'])->name('game.pending');
    Route::get('game/session/{code}', [\App\Http\Controllers\GameSessionController::class, 'show'])->name('game.session.show');

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
         ->name('analytics.index')->middleware('role:admin,icl,ctl');
});

require __DIR__ . '/auth.php';
