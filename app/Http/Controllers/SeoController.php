<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    /**
     * Generiert die Sitemap
     */
    public function sitemap()
    {
        if (!config('seo.sitemap_enabled', true)) {
            abort(404);
        }

        $cacheKey = 'seo_sitemap';
        $cacheDuration = config('seo.sitemap_cache_duration', 3600);

        $sitemap = Cache::remember($cacheKey, $cacheDuration, function () {
            $urls = [];
            $baseUrl = config('app.url');

            // Startseite
            $urls[] = [
                'loc' => $baseUrl,
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];

            // Weitere öffentliche Seiten können hier hinzugefügt werden
            // Beispiel: Login-Seite
            if (Route::has('login')) {
                $urls[] = [
                    'loc' => route('login'),
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.5',
                ];
            }

            return $urls;
        });

        return response()->view('seo.sitemap', [
            'urls' => $sitemap,
        ])->header('Content-Type', 'application/xml');
    }

    /**
     * Generiert robots.txt
     */
    public function robots()
    {
        $disallowPaths = config('seo.robots_disallow_paths', []);
        $allow = config('seo.robots_allow', true);

        return response()->view('seo.robots', [
            'allow' => $allow,
            'disallowPaths' => $disallowPaths,
            'sitemapUrl' => config('seo.sitemap_enabled', true) ? url('/sitemap.xml') : null,
        ])->header('Content-Type', 'text/plain');
    }
}

