<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust all proxies so Cloudflare tunnel headers are respected
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin'       => \App\Http\Middleware\AdminMiddleware::class,
            'role'        => \App\Http\Middleware\CheckRole::class,
            'feature'     => \App\Http\Middleware\FeatureAccess::class,
            'maintenance' => \App\Http\Middleware\MaintenanceMiddleware::class,
            'birthday'    => \App\Http\Middleware\BirthdayMiddleware::class,
            'daily-verse-popup' => \App\Http\Middleware\DailyVersePopupMiddleware::class,
            'new-upload-popup' => \App\Http\Middleware\NewUploadPopupMiddleware::class,
            'tenant.selected' => \App\Http\Middleware\EnsureTenantSelected::class,
        ]);
        // Apply globally on all web routes
        $middleware->appendToGroup('web', \App\Http\Middleware\MaintenanceMiddleware::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\BirthdayMiddleware::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\DailyVersePopupMiddleware::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\NewUploadPopupMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withEvents(discover: [
        __DIR__.'/../app/Listeners',
    ])
    ->create();
