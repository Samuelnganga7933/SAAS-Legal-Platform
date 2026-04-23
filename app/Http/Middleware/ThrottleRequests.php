<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests extends \Illuminate\Http\Middleware\ThrottleRequests
{
    /**
     * Resolve the rate limiter instance.
     *
     * @return \Illuminate\Cache\RateLimiter
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * Resolve request signature.
     *
     * @return string
     */
    protected function resolveRequestSignature($request)
    {
        // Use IP address as the key for rate limiting
        return sha1(
            $request->method().
            '|'.$request->getHost().
            '|'.$this->getClientIp($request)
        );
    }

    /**
     * Get the client IP address
     */
    protected function getClientIp($request)
    {
        // Trust proxies if application is behind a proxy
        if ($request->hasHeader('CF-Connecting-IP')) {
            return $request->header('CF-Connecting-IP');
        } elseif ($request->hasHeader('X-Forwarded-For')) {
            return explode(',', $request->header('X-Forwarded-For'))[0];
        }

        return $request->ip();
    }

    /**
     * Create a "too many requests" response.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($request, $limit, $remaining, $reset)
    {
        $headers = $this->getHeaders($limit, $remaining, $reset);

        $retryAfter = header_register_retry_after ? (int) ceil(($reset - time()) / 60) : null;

        return response('Too many requests. Please try again in ' . $retryAfter . ' minutes.', 429, $headers);
    }
}
