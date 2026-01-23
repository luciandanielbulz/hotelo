<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // CSP-Header mit Google reCAPTCHA-Unterstützung
        // Nur setzen, wenn nicht bereits gesetzt (z.B. von Server)
        if (!$response->headers->has('Content-Security-Policy')) {
            // Erlaube localhost für Development (localhost funktioniert für IPv4 und IPv6)
            $localhostSources = "http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*";
            
            $csp = [
                "default-src 'self' " . $localhostSources,
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com " . $localhostSources,
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://fonts.bunny.net " . $localhostSources,
                "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com data: " . $localhostSources,
                "img-src 'self' data: https: blob: http: " . $localhostSources,
                "connect-src 'self' https://www.google.com https://www.gstatic.com " . $localhostSources,
                "frame-src 'self' https://www.google.com",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'none'",
            ];

            $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        }

        return $response;
    }
}
