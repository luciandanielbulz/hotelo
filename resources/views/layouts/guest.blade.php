<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $seoData = $seoData ?? [];
            $pageTitle = isset($seoData['title']) && !empty($seoData['title']) 
                ? $seoData['title'] . ' - ' . config('app.name', 'Laravel')
                : config('app.name', 'Laravel');
            $seoData['title'] = $pageTitle;
            $structuredData = $structuredData ?? [];
            
            // Füge Standard-Organisation hinzu, wenn nicht vorhanden
            if (empty($structuredData)) {
                $structuredData[] = \App\Helpers\SeoHelper::organizationStructuredData();
            }
        @endphp
        
        <title>{{ $pageTitle }}</title>
        
        {{-- SEO Meta-Tags --}}
        <x-seo-meta :seoData="$seoData" :canonicalUrl="$canonicalUrl ?? url()->current()" :structuredData="$structuredData" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time() }}&t={{ time() }}">
        <link rel="alternate icon" type="image/svg+xml" href="{{ \App\Helpers\TemplateHelper::getFaviconPath() }}&t={{ time() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Main Content -->
        {{ $slot }}
    </body>
</html>
