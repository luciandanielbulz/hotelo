<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="description" content="Venditio - Ihre Business-Management-Lösung">
        <meta name="theme-color" content="#4CAF50">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
        
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('logo/VenditioIcon.svg') }}">
        <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        
        <!-- Apple Touch Icons -->
        <link rel="apple-touch-icon" href="{{ asset('logo/Logo transparent.png') }}">
        
        <!-- Web App Manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">

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
