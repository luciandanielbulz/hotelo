<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $seoData = $seoData ?? [
                'title' => 'Zimmervermietung - ' . config('app.name'),
                'description' => 'Buchen Sie Ihre Zimmer online',
            ];
            $pageTitle = $seoData['title'] ?? 'Zimmervermietung';
        @endphp
        
        <title>{{ $pageTitle }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-white">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-gray-700 hover:text-gray-900 font-medium">Startseite</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('booking.rooms.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">Zimmer</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 font-medium">Anmelden</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-100 border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-gray-600">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
            </div>
        </footer>
    </body>
</html>
