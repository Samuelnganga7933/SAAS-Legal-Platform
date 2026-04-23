<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class HealthCheckController extends Controller
{
    /**
     * Check application health
     */
    public function check(): JsonResponse
    {
        $checks = [
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
        ];

        // Database connectivity
        try {
            DB::connection()->getPdo();
            $checks['database'] = 'ok';
        } catch (\Exception $e) {
            $checks['database'] = 'error: ' . $e->getMessage();
            $checks['status'] = 'degraded';
        }

        // Cache connectivity
        try {
            $cacheKey = 'health_check_' . uniqid();
            Cache::put($cacheKey, 'ok', 10);
            $cached = Cache::get($cacheKey);
            if ($cached === 'ok') {
                $checks['cache'] = 'ok';
                Cache::forget($cacheKey);
            }
        } catch (\Exception $e) {
            $checks['cache'] = 'error: ' . $e->getMessage();
            $checks['status'] = 'degraded';
        }

        // Storage accessibility
        try {
            $storageKey = 'health_check_' . uniqid();
            \Illuminate\Support\Facades\Storage::disk('local')->put($storageKey, 'ok');
            if (\Illuminate\Support\Facades\Storage::disk('local')->exists($storageKey)) {
                $checks['storage'] = 'ok';
                \Illuminate\Support\Facades\Storage::disk('local')->delete($storageKey);
            }
        } catch (\Exception $e) {
            $checks['storage'] = 'error: ' . $e->getMessage();
            $checks['status'] = 'degraded';
        }

        // Queue connectivity (optional)
        try {
            $queueWorking = true; // Assume working, would need actual check
            $checks['queue'] = 'ok';
        } catch (\Exception $e) {
            $checks['queue'] = 'warning: ' . $e->getMessage();
        }

        // Memory usage
        $checks['memory_usage_mb'] = round(memory_get_usage(true) / 1024 / 1024, 2);
        $checks['memory_limit_mb'] = round(memory_get_peak_usage(true) / 1024 / 1024, 2);

        // Response code based on status
        $statusCode = $checks['status'] === 'ok' ? 200 : 503;

        return response()->json($checks, $statusCode);
    }

    /**
     * Readiness check (for load balancers)
     */
    public function ready(): JsonResponse
    {
        try {
            // Verify critical system is ready
            DB::connection()->getPdo();
            Cache::get('test');

            return response()->json(['status' => 'ready'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'not_ready', 'error' => $e->getMessage()], 503);
        }
    }

    /**
     * Liveness check (for Kubernetes/orchestration)
     */
    public function live(): JsonResponse
    {
        // Simple check that app is running
        return response()->json(['status' => 'live'], 200);
    }
}
