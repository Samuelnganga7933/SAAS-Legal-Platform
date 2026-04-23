<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Check if user has an active subscription
        if (!$user->hasActiveSubscription()) {
            return redirect('/billing/subscribe')
                ->with('warning', 'Your subscription has expired. Please renew to continue.');
        }

        return $next($request);
    }
}
