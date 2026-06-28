<?php

namespace App\Providers;

use App\Events\DevotionApproved;
use App\Events\DevotionCreated;
use App\Listeners\CheckDevotionAchievements;
use App\Managers\NotificationManager;
use App\Managers\SettingsManager;
use App\Managers\ThemeManager;
use App\Models\Album;
use App\Models\Message;
use App\Models\Photo;
use App\Policies\AlbumPolicy;
use App\Policies\MessagePolicy;
use App\Policies\PhotoPolicy;
use App\Services\AchievementService;
use App\Services\BannerService;
use App\Services\LeaderboardService;
use App\Services\PointService;
use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AchievementService::class);
        $this->app->singleton(BannerService::class);
        $this->app->singleton(SettingsManager::class);
        $this->app->singleton(ThemeManager::class);
        $this->app->singleton(NotificationManager::class);
        $this->app->singleton(PointService::class);
        $this->app->singleton(LeaderboardService::class);
        $this->app->singleton(QrCodeService::class);

        // Policies
        $this->app['Illuminate\Contracts\Auth\Access\Gate']->policy(Album::class, AlbumPolicy::class);
        $this->app['Illuminate\Contracts\Auth\Access\Gate']->policy(Photo::class, PhotoPolicy::class);
        $this->app['Illuminate\Contracts\Auth\Access\Gate']->policy(Message::class, MessagePolicy::class);
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        Paginator::useBootstrapFive();

        Event::subscribe(CheckDevotionAchievements::class);

        // Share settings and theme with all views
        View::composer('*', function ($view) {
            $settings = app(SettingsManager::class);
            $theme    = app(ThemeManager::class);

            $view->with('_settings', $settings);
            $view->with('_theme',    $theme);
            $view->with('_appName',  $settings->appName());

            if (auth()->check()) {
                $view->with('_notifCount', app(NotificationManager::class)->unreadCount(auth()->user()));
            } else {
                $view->with('_notifCount', 0);
            }
        });

        // Blade directive for settings
        Blade::directive('setting', function ($key) {
            return "<?php echo app(\App\Managers\SettingsManager::class)->get({$key}); ?>";
        });
    }
}
