<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Standard SEO-Einstellungen
    |--------------------------------------------------------------------------
    */

    'default_title' => env('SEO_DEFAULT_TITLE', 'quickBill - Rechnungsverwaltung einfach gemacht'),
    
    'default_description' => env('SEO_DEFAULT_DESCRIPTION', 'quickBill ist eine benutzerfreundliche Lösung zur mühelosen Verwaltung Ihrer Rechnungen. Erstellen, verfolgen und versenden Sie Rechnungen schnell und einfach - alles an einem Ort.'),
    
    'default_keywords' => env('SEO_DEFAULT_KEYWORDS', 'Rechnungsverwaltung, Rechnung erstellen, Rechnung verwalten, Rechnungssoftware, quickBill, KMU-Office, Venditio'),
    
    'default_image' => env('SEO_DEFAULT_IMAGE', 'logo/quickBill-Logo-alone.png'),
    
    'default_author' => env('SEO_DEFAULT_AUTHOR', 'Bulz'),
    
    'twitter_handle' => env('SEO_TWITTER_HANDLE', '@quickBill'),
    
    'facebook_app_id' => env('SEO_FACEBOOK_APP_ID', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Sitemap-Einstellungen
    |--------------------------------------------------------------------------
    */
    
    'sitemap_enabled' => env('SEO_SITEMAP_ENABLED', true),
    
    'sitemap_cache_duration' => env('SEO_SITEMAP_CACHE_DURATION', 3600), // 1 Stunde
    
    /*
    |--------------------------------------------------------------------------
    | Robots.txt Einstellungen
    |--------------------------------------------------------------------------
    */
    
    'robots_allow' => env('SEO_ROBOTS_ALLOW', true),
    
    'robots_disallow_paths' => [
        '/admin',
        '/api',
        '/storage',
        '/dashboard',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Canonical URL Einstellungen
    |--------------------------------------------------------------------------
    */
    
    'canonical_enabled' => env('SEO_CANONICAL_ENABLED', true),
    
    /*
    |--------------------------------------------------------------------------
    | Open Graph Einstellungen
    |--------------------------------------------------------------------------
    */
    
    'og_enabled' => env('SEO_OG_ENABLED', true),
    
    /*
    |--------------------------------------------------------------------------
    | Twitter Card Einstellungen
    |--------------------------------------------------------------------------
    */
    
    'twitter_card_enabled' => env('SEO_TWITTER_CARD_ENABLED', true),
    
    'twitter_card_type' => env('SEO_TWITTER_CARD_TYPE', 'summary_large_image'),
];



