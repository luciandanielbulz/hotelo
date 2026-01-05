<?php

namespace App\Helpers;

class SeoHelper
{
    /**
     * Generiert Meta-Tags für SEO
     */
    public static function metaTags(array $data = []): array
    {
        $defaults = [
            'title' => config('seo.default_title', config('app.name')),
            'description' => config('seo.default_description', ''),
            'keywords' => config('seo.default_keywords', ''),
            'image' => config('seo.default_image', asset('logo/quickBill-Logo-alone.png')),
            'url' => url()->current(),
            'type' => 'website',
            'locale' => app()->getLocale(),
            'site_name' => config('app.name'),
        ];

        $data = array_merge($defaults, $data);

        // Stelle sicher, dass URLs vollständig sind
        if (!empty($data['image']) && !filter_var($data['image'], FILTER_VALIDATE_URL)) {
            $data['image'] = url($data['image']);
        }

        if (!empty($data['url']) && !filter_var($data['url'], FILTER_VALIDATE_URL)) {
            $data['url'] = url($data['url']);
        }

        return $data;
    }

    /**
     * Generiert strukturierte Daten (JSON-LD)
     */
    public static function structuredData(array $data = []): array
    {
        $defaults = [
            '@context' => 'https://schema.org',
            '@type' => 'WebApplication',
            'name' => config('app.name'),
            'description' => config('seo.default_description', ''),
            'url' => config('app.url'),
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
        ];

        return array_merge($defaults, $data);
    }

    /**
     * Generiert Breadcrumb-Strukturierte Daten
     */
    public static function breadcrumbStructuredData(array $items): array
    {
        $breadcrumbList = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];

        foreach ($items as $position => $item) {
            $breadcrumbList['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'] ?? '',
                'item' => $item['url'] ?? '',
            ];
        }

        return $breadcrumbList;
    }

    /**
     * Generiert Organization-Strukturierte Daten
     */
    public static function organizationStructuredData(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'logo' => url(config('seo.default_image', 'logo/quickBill-Logo-alone.png')),
        ];
    }
}



