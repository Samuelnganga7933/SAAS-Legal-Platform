<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME type sniffing
        $response->header('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection
        $response->header('X-XSS-Protection', '1; mode=block');

        // Prevent clickjacking
        $response->header('X-Frame-Options', 'SAMEORIGIN');

        // Content Security Policy - strict by default
        $response->header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://www.google.com https://www.gstatic.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' data: https://fonts.gstatic.com; connect-src 'self' https://api.stripe.com; frame-src 'self' https://js.stripe.com https://hooks.stripe.com https://www.google.com https://www.gstatic.com;");

        // Force HTTPS
        if (config('app.env') === 'production') {
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Control Referrer Policy
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy
        $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=(self)');

        // Disable client-side caching for sensitive content
        if ($request->path() === '/logout' || str_starts_with($request->path(), '/worker/')) {
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
        }

        return $response;
    }
}
