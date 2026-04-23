<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sentry Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration is for Sentry error tracking and monitoring.
    | Sentry will not be automatically initialized until you set a DSN.
    |
    */

    'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),

    // Set tracesSampleRate to 1.0 to capture 100% of transactions for performance monitoring.
    'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE', 0.1),

    // Set profiles_sample_rate to 1.0 to profile 100% of sampled transactions.
    // We recommend adjusting this value in production.
    'profiles_sample_rate' => env('SENTRY_PROFILES_SAMPLE_RATE', 0.1),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | The application environment which will be used to identify your project.
    |
    */

    'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV', 'production')),

    /*
    |--------------------------------------------------------------------------
    | Release
    |--------------------------------------------------------------------------
    |
    | The release of your application. Will detect automatically from git if not set.
    |
    */

    'release' => env('SENTRY_RELEASE', trim(exec('git rev-parse --short HEAD 2>/dev/null') ?: '')),

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs
    |--------------------------------------------------------------------------
    |
    | Breadcrumbs provide a timeline of what happened during the request.
    |
    */

    'breadcrumbs' => [
        'logs' => env('SENTRY_BREADCRUMBS_LOGS', true),
        'sql_queries' => env('SENTRY_BREADCRUMBS_SQL_QUERIES', true),
        'sql_bindings' => env('SENTRY_BREADCRUMBS_SQL_BINDINGS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Before Sending The Event
    |--------------------------------------------------------------------------
    |
    | This callback is called right before the event is sent to Sentry server.
    | Use it to filter out sensitive data or add additional context.
    |
    */

    'before_send' => function (\Sentry\Event $event) {
        // Filter out custom errors if needed
        return $event;
    },

    /*
    |--------------------------------------------------------------------------
    | Before Breadcrumb
    |--------------------------------------------------------------------------
    |
    | This callback is called right before breadcrumb is recorded.
    |
    */

    'before_breadcrumb' => function (\Sentry\Breadcrumb $breadcrumb) {
        return $breadcrumb;
    },

    /*
    |--------------------------------------------------------------------------
    | Integration Options
    |--------------------------------------------------------------------------
    |
    | Options for individual integrations.
    |
    */

    'integrations' => [
        \Sentry\Laravel\Integration::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Attach Stack Traces
    |--------------------------------------------------------------------------
    |
    | Attach stack traces to all messages.
    |
    */

    'attach_stack_traces' => env('SENTRY_ATTACH_STACK_TRACES', true),

    /*
    |--------------------------------------------------------------------------
    | Enable Performance Monitoring
    |--------------------------------------------------------------------------
    |
    | This will enable Sentry's performance monitoring.
    |
    */

    'enable_performance_monitoring' => env('SENTRY_ENABLE_PERFORMANCE_MONITORING', false),
];
