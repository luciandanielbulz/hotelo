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
            $baseUrl = rtrim(config('app.url'), '/');

            $publicPages = [
                ['url' => $baseUrl, 'changefreq' => 'daily', 'priority' => '1.0'],
                ['route' => 'login', 'changefreq' => 'monthly', 'priority' => '0.6'],
                ['route' => 'register', 'changefreq' => 'monthly', 'priority' => '0.5'],
                ['route' => 'about', 'changefreq' => 'monthly', 'priority' => '0.8'],
                ['route' => 'pricing', 'changefreq' => 'weekly', 'priority' => '0.9'],
                ['route' => 'contact.form', 'changefreq' => 'monthly', 'priority' => '0.8'],
                ['route' => 'impressum', 'changefreq' => 'yearly', 'priority' => '0.3'],
                ['route' => 'privacy', 'changefreq' => 'yearly', 'priority' => '0.3'],
                ['route' => 'cookies', 'changefreq' => 'yearly', 'priority' => '0.3'],
                ['route' => 'features.invoices', 'changefreq' => 'monthly', 'priority' => '0.7'],
                ['route' => 'features.offers', 'changefreq' => 'monthly', 'priority' => '0.7'],
                ['route' => 'features.customers', 'changefreq' => 'monthly', 'priority' => '0.7'],
                ['route' => 'features.pdfs', 'changefreq' => 'monthly', 'priority' => '0.7'],
                ['route' => 'features.sending', 'changefreq' => 'monthly', 'priority' => '0.7'],
                ['route' => 'features.analytics', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ];

            foreach ($publicPages as $page) {
                if (isset($page['url'])) {
                    $urls[] = [
                        'loc' => $page['url'],
                        'lastmod' => now()->toAtomString(),
                        'changefreq' => $page['changefreq'],
                        'priority' => $page['priority'],
                    ];
                } elseif (isset($page['route']) && Route::has($page['route'])) {
                    $urls[] = [
                        'loc' => route($page['route']),
                        'lastmod' => now()->toAtomString(),
                        'changefreq' => $page['changefreq'],
                        'priority' => $page['priority'],
                    ];
                }
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

