<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only redirect in production environment and if not using a local development domain
        if ($this->shouldForceHttps()) {
            if (!$request->secure() && !$request->is('debugbar*')) {
                // Check if request has proper headers for HTTPS redirect
                $scheme = $request->getScheme();
                $host = $request->getHost();

                // Log the redirect for debugging
                if (config('app.debug')) {
                    Log::info("HTTP to HTTPS redirect for: {$request->url()}");
                }

                return redirect()->secure($request->getRequestUri(), 301);
            }
        }

        return $next($request);
    }

    /**
     * Determine if HTTPS should be forced
     */
    private function shouldForceHttps(): bool
    {
        $environment = config('app.env');
        $forceHttps = env('FORCE_HTTPS', null);

        // Force HTTPS only if:
        // 1. FORCE_HTTPS is explicitly set to true, OR
        // 2. In production environment with FORCE_HTTPS not set (defaults to true in production)

        if ($forceHttps === true || $forceHttps === 'true') {
            return true;
        }

        if ($environment === 'production' && $forceHttps !== false && $forceHttps !== 'false') {
            return true;
        }

        return false;
    }
}
