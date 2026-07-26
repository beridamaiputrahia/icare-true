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


// ── Cron eksternal (cron-job.org) ────────────────────────────────────────
// Render Cron Job butuh kartu kredit terdaftar, jadi Laravel Scheduler
// dipicu lewat layanan ping gratis (cron-job.org dkk) yang memanggil route
// ini setiap menit. Dilindungi token rahasia di URL, bukan middleware auth,
// karena dipanggil tanpa sesi login.
Route::get('/cron/run-scheduler/{token}', function (string $token) {
    abort_unless(hash_equals((string) config('app.cron_token'), $token), 403);

    \Illuminate\Support\Facades\Artisan::call('schedule:run');

    return response('OK', 200);
})->name('cron.run-scheduler');



Route::get('/manifest.json', function (\Illuminate\Http\Request $request) {
    // Identifikasi tenant: dari user login (jika ada) atau dari subdomain.
    // Selaras dengan SettingsManager::resolveTenantId() -- superadmin yang
    // belum "masuk sebagai" grup tertentu jatuh ke tenant sistem khusus
    // (bukan grup jemaat manapun), bukan null/tenant_id kolom database yang
    // untuk superadmin memang selalu null by design.
    $tenantId = null;
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'superadmin') {
            $tenantId = session('active_tenant_id') ?? \App\Models\Tenant::defaultBrandingTenant()->id;
        } else {
            $tenantId = $user->tenant_id;
        }
    } else {
        // Coba deteksi dari subdomain: {slug}.domain.com
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        $tenant = \App\Models\Tenant::where('slug', $subdomain)->where('is_active', true)->first();
        $tenantId = $tenant?->id;
    }

    $logo         = \App\Models\AppSetting::get('logo', null, $tenantId);
    $appName      = \App\Models\AppSetting::get('app_name', 'I Care True', $tenantId);
    $sidebarColor = \App\Models\AppSetting::get('sidebar_color', '#1e293b', $tenantId);
    $primaryColor = \App\Models\AppSetting::get('primary_color', '#2563eb', $tenantId);
    $fallbackIcon = '/pwa-icons/icon.svg';
    $icon96       = \App\Support\FileUrl::square($logo, 96)  ?? $fallbackIcon;
    $icon192      = \App\Support\FileUrl::square($logo, 192) ?? $fallbackIcon;
    $icon512      = \App\Support\FileUrl::square($logo, 512) ?? $fallbackIcon;

    $iconType = fn (string $url) => match (strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION))) {
        'jpg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'svg'  => 'image/svg+xml',
        default => 'image/png',
    };

    $manifest = [
        'name'             => $appName,
        'short_name'       => $appName,
        'description'      => 'Pusat Informasi Komunitas Rohani Kristen',
        'start_url'        => '/',
        'scope'            => '/',
        'display'          => 'standalone',
        'orientation'      => 'portrait',
        'background_color' => $sidebarColor,
        'theme_color'      => $primaryColor,
        'categories'       => ['lifestyle', 'social'],
        'lang'             => 'id',
        'icons'            => [
            ['src' => $icon192, 'sizes' => '192x192', 'type' => $iconType($icon192), 'purpose' => 'any maskable'],
            ['src' => $icon512, 'sizes' => '512x512', 'type' => $iconType($icon512), 'purpose' => 'any maskable'],
        ],
        'shortcuts' => [
            ['name' => 'Dashboard', 'url' => '/dashboard', 'icons' => [['src' => $icon96, 'sizes' => '96x96']]],
            ['name' => 'Jadwal',    'url' => '/schedules', 'icons' => [['src' => $icon96, 'sizes' => '96x96']]],
            ['name' => 'Doa',       'url' => '/prayers',   'icons' => [['src' => $icon96, 'sizes' => '96x96']]],
        ],
    ];
    return response()->json($manifest)->header('Content-Type', 'application/manifest+json');
})->name('manifest');
Route::get('game/assets/feature-js', [\App\Http\Controllers\GameController::class, 'serveJsx'])->name('game.jsx');

// ── Superadmin: kelola tenant & tenant switcher ─────────────────────────────
// Di luar grup 'tenant.selected' di bawah, supaya superadmin bisa akses
// halaman pilih tenant SEBELUM tenant aktif ter-set di session (menghindari
// redirect loop).
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('tenants/select',  [\App\Http\Controllers\Superadmin\TenantController::class, 'select'])->name('tenants.select');
    Route::post('tenants/switch', [\App\Http\Controllers\Superadmin\TenantController::class, 'switch'])->name('tenants.switch');

    // TEMP: backfill conversation global/leader untuk tenant lama yang dibuat
    // sebelum TenantController::store() membuatnya otomatis. Hapus setelah dijalankan sekali.
    Route::get('_backfill-conversations', function () {
        \Illuminate\Support\Facades\Artisan::call('chat:backfill-conversations');
        return response('<pre>' . e(\Illuminate\Support\Facades\Artisan::output()) . '</pre>');
    });

    // TEMP DEBUG: baca tail laravel.log untuk diagnosa kenapa push notification
    // tidak muncul sebagai pop-up sistem. Hapus setelah selesai.
    Route::get('_debug-log', function () {
        $logPath = storage_path('logs/laravel.log');
        if (!file_exists($logPath)) {
            return response('<pre>(belum ada file log)</pre>');
        }
        return response('<pre>' . e(substr(file_get_contents($logPath), -12000)) . '</pre>');
    });

    // TEMP DEBUG: lihat SEMUA push subscription per user (untuk cek apakah
    // HP & laptop tersimpan sebagai 2 endpoint terpisah yang benar).
    Route::get('_debug-subscriptions', function () {
        $out = '';
        foreach (\App\Models\User::where('is_active', true)->get() as $u) {
            $subs = \Illuminate\Support\Facades\DB::table('push_subscriptions')
                ->where('subscribable_id', $u->id)
                ->where('subscribable_type', \App\Models\User::class)
                ->get();
            if ($subs->isEmpty()) continue;
            $out .= "User #{$u->id} ({$u->name}):\n";
            foreach ($subs as $s) {
                $out .= "  - {$s->endpoint}\n    created_at={$s->created_at}\n";
            }
            $out .= "\n";
        }
        return response('<pre>' . e($out) . '</pre>');
    });

    // TEMP DEBUG: cek langsung apakah foto tersimpan & bisa diakses di Cloudinary.
    Route::get('_debug-photo/{photo}', function (\App\Models\Photo $photo) {
        $out = "Photo #{$photo->id}\n";
        $out .= "file_path (di DB): {$photo->file_path}\n";
        $out .= "FILESYSTEM_DISK aktif: " . config('filesystems.default') . "\n\n";

        $disk = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'));

        try {
            $exists = $disk->exists($photo->file_path);
            $out .= "exists(): " . var_export($exists, true) . "\n";
        } catch (\Throwable $e) {
            $out .= "exists() ERROR: " . get_class($e) . ': ' . $e->getMessage() . "\n";
        }

        $out .= "FileUrl::of() (dipakai app, tidak lewat Admin API): " . (\App\Support\FileUrl::of($photo->file_path) ?? 'null') . "\n";

        return response('<pre>' . e($out) . '</pre>');
    });

    Route::resource('tenants', \App\Http\Controllers\Superadmin\TenantController::class)
        ->except(['select', 'switch']);

    Route::resource('superadmins', \App\Http\Controllers\Superadmin\SuperAdminController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::put('superadmins/{superadmin}/role', [\App\Http\Controllers\Superadmin\SuperAdminController::class, 'updateRole'])
        ->name('superadmins.role');
    Route::put('superadmins/{superadmin}/group', [\App\Http\Controllers\Superadmin\SuperAdminController::class, 'updateGroup'])
        ->name('superadmins.group');

    // Bank soal 4 mini-game rohani
    Route::resource('game-questions', \App\Http\Controllers\Superadmin\GameQuestionController::class)
        ->except(['show']);
    Route::put('game-questions/{game_question}/toggle', [\App\Http\Controllers\Superadmin\GameQuestionController::class, 'toggle'])
        ->name('game-questions.toggle');
});

// ── Authenticated ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'birthday', 'tenant.selected'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',             [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',    [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile',          [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Push notification subscription (browser/HP)
    Route::post('/push-subscriptions',   [\App\Http\Controllers\PushSubscriptionController::class, 'store'])->name('push-subscriptions.store');
    Route::delete('/push-subscriptions', [\App\Http\Controllers\PushSubscriptionController::class, 'destroy'])->name('push-subscriptions.destroy');

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
    Route::get('game/members', [GameController::class, 'members'])->name('game.members');
    Route::post('game/challenge',     [\App\Http\Controllers\GameSessionController::class, 'challenge'])->name('game.challenge');
    Route::post('game/respond',       [\App\Http\Controllers\GameSessionController::class, 'respond'])->name('game.respond');
    Route::post('game/start',         [\App\Http\Controllers\GameSessionController::class, 'start'])->name('game.start');
    Route::post('game/leave',         [\App\Http\Controllers\GameSessionController::class, 'leave'])->name('game.leave');
    Route::post('game/resolve-leave', [\App\Http\Controllers\GameSessionController::class, 'resolveLeave'])->name('game.resolve-leave');
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
