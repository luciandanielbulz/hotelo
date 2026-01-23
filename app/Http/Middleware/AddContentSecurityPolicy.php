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

        // Entferne alle vorhandenen CSP-Header (vom Server gesetzt)
        $response->headers->remove('Content-Security-Policy');
        $response->headers->remove('Content-Security-Policy-Report-Only');
        $response->headers->remove('X-Content-Security-Policy');

        // CSP-Header mit Google reCAPTCHA-Unterstützung
        // Immer setzen, um sicherzustellen, dass alle benötigten Domains erlaubt sind
        // Erlaube localhost für Development (nur in Development)
        $isLocal = app()->environment('local', 'testing');
        $localhostSources = $isLocal ? "http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*" : "";
        
        $csp = [
            "default-src 'self'" . ($localhostSources ? " " . $localhostSources : ""),
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com" . ($localhostSources ? " " . $localhostSources : ""),
            "script-src-elem 'self' https://www.google.com https://www.gstatic.com" . ($localhostSources ? " " . $localhostSources : ""),
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://fonts.bunny.net" . ($localhostSources ? " " . $localhostSources : ""),
            "style-src-elem 'self' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://fonts.bunny.net" . ($localhostSources ? " " . $localhostSources : ""),
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com data:" . ($localhostSources ? " " . $localhostSources : ""),
            "img-src 'self' data: https: blob: http:" . ($localhostSources ? " " . $localhostSources : ""),
            "connect-src 'self' https://www.google.com https://www.gstatic.com" . ($localhostSources ? " " . $localhostSources : ""),
            "frame-src 'self' https://www.google.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ];

        // CSP immer setzen (überschreibt eventuelle Server-CSP)
        $response->headers->set('Content-Security-Policy', implode('; ', $csp), true);

        return $response;
    }
}
