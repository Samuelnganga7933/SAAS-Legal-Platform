<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsClient
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

        // Allow clients only (and legacy is_admin false)
        if ($user->role === 'client' && !in_array($user->role, ['admin', 'worker', 'team_member'])) {
            return $next($request);
        }

        // Redirect workers/admins to their own portals
        if (in_array($user->role, ['worker', 'team_member'])) {
            return redirect('/worker/dashboard')->with('error', 'Workers cannot access client portal features.');
        }

        if ($user->role === 'admin' || $user->is_admin) {
            return redirect('/admin/dashboard')->with('error', 'Admins cannot access client portal features.');
        }

        abort(403, 'Unauthorized access.');
    }
}
