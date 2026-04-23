<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
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

        // Check new role system first, fallback to is_admin for legacy support
        if ($user->role === 'admin' || $user->is_admin) {
            return $next($request);
        }

        abort(403, 'Unauthorized. Admin access required.');
    }
}
