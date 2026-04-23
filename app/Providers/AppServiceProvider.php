<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure rate limiting
        $this->configureRateLimiting();
        
        // Register Sentry (if DSN is configured)
        if (config('sentry.dsn')) {
            \Sentry\Laravel\Integration::traceMiddlewareAutoInstrumentation();
        }

        // Share unread message count with all views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $unreadCount = \App\Models\Message::where('recipient_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('is_read', false)
                    ->count();
                $view->with('unreadMessageCount', $unreadCount);
            }
        });
    }

    /**
     * Configure rate limiting for the application
     */
    private function configureRateLimiting(): void
    {
        $rateLimiter = app(RateLimiter::class);

        // API rate limiting - 60 requests per minute
        $rateLimiter->for('api', function ($request) {
            return \Illuminate\RateLimiting\Limit::perMinute(60)
                ->by(optional($request->user())->id ?: $request->ip());
        });

        // Authentication rate limiting - 5 attempts per minute
        $rateLimiter->for('auth', function ($request) {
            return \Illuminate\RateLimiting\Limit::perMinute(5)
                ->by($request->ip());
        });

        // Messaging rate limiting - 30 messages per hour
        $rateLimiter->for('messaging', function ($request) {
            return \Illuminate\RateLimiting\Limit::perHour(30)
                ->by(optional($request->user())->id ?: $request->ip());
        });

        // Generic rate limit - 60 requests per minute
        $rateLimiter->for('default', function ($request) {
            return \Illuminate\RateLimiting\Limit::perMinute(60)
                ->by($request->ip());
        });
    }
}
