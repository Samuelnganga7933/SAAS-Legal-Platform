<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCookieConsent
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if cookie consent has been provided
        if (!$request->hasCookie('cookie_consent')) {
            // Create cookie consent tracking cookie on first visit
            $response->cookie(
                'cookie_consent_shown',
                '1',
                60 * 24 * 365, // 1 year
                '/',
                null,
                false,
                true // httpOnly
            );
        }

        return $response;
    }
}
