<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\ClientDocument;
use App\Models\ClientCase;
use App\Policies\TaskPolicy;
use App\Policies\ClientDocumentPolicy;
use App\Policies\ClientCasePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Task::class => TaskPolicy::class,
        ClientDocument::class => ClientDocumentPolicy::class,
        ClientCase::class => ClientCasePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate for subscription checks
        Gate::define('has-active-subscription', function ($user) {
            return $user->hasActiveSubscription();
        });

        // Gate for worker role
        Gate::define('is-worker', function ($user) {
            return $user->isWorker();
        });

        // Gate for client role
        Gate::define('is-client', function ($user) {
            return $user->isClient();
        });

        // Gate for admin role
        Gate::define('is-admin', function ($user) {
            return $user->isAdminUser();
        });
    }
}
