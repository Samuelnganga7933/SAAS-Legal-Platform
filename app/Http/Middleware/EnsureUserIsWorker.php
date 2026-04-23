<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsWorker
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

        if (!in_array($user->role, ['worker', 'team_member'])) {
            abort(403, 'Unauthorized. Only workers can access this area.');
        }

        return $next($request);
    }
}
