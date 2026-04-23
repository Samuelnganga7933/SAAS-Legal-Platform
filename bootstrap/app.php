<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Temporarily disable SecurityHeaders to diagnose CSRF issue
        // $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Middleware aliases for conditional application
        $middleware->alias([
            'ensure.user.is.admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'ensure.user.is.client' => \App\Http\Middleware\EnsureUserIsClient::class,
            'ensure.user.is.worker' => \App\Http\Middleware\EnsureUserIsWorker::class,
            'ensure.subscription.active' => \App\Http\Middleware\EnsureSubscriptionActive::class,
            'permission' => \App\Http\Middleware\RequirePermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
