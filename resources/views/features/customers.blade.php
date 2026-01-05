<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $seoData = [
                'title' => 'Kundenverwaltung - quickBill',
                'description' => 'Verwalten Sie alle Ihre Kunden zentral mit quickBill. Kontaktdaten, Historie und alle zugehörigen Dokumente übersichtlich organisiert.',
                'keywords' => 'Kundenverwaltung, CRM, Kundenverwaltungssoftware',
                'image' => asset('logo/quickBill-Logo-alone.png'),
            ];
            $structuredData = [\App\Helpers\SeoHelper::organizationStructuredData()];
        @endphp
        <title>{{ $seoData['title'] }}</title>
        <x-seo-meta :seoData="$seoData" :canonicalUrl="url()->current()" :structuredData="$structuredData" />
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time() }}&t={{ time() }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-white">
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}"><img src="{{ asset('logo/quickBill-Logo-alone.png') }}" alt="quickBill" class="h-10 w-auto"></a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/') }}" class="text-gray-700 hover:text-gray-900 font-medium">Zurück</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('contact.form') }}" class="bg-blue-900 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-800 transition-colors">Angebot einholen</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <section class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-white">
            <div class="max-w-7xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 rounded-xl mb-6">
                    <svg class="w-10 h-10 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Zentrale<br>
                    <span class="text-blue-900">Kundenverwaltung</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                    Verwalten Sie alle Ihre Kunden zentral. Kontaktdaten, Historie und alle zugehörigen Dokumente übersichtlich organisiert.
                </p>
            </div>
        </section>

        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Alles an einem Ort</h2>
                        <p class="text-lg text-gray-600 mb-4 leading-relaxed">
                            Alle Kundendaten, Rechnungen, Angebote und Kommunikation an einem zentralen Ort. Schneller Zugriff auf alle wichtigen Informationen.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Vollständige Kontaktdaten</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Vollständige Rechnungs- und Angebotshistorie</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Schnelle Suche und Filterung</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-8 border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Ihre Vorteile</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100">
                                        <svg class="h-6 w-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-base font-medium text-gray-900">Schneller Zugriff</h4>
                                    <p class="text-sm text-gray-600">Alle Kundendaten sofort verfügbar</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100">
                                        <svg class="h-6 w-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-base font-medium text-gray-900">Vollständige Historie</h4>
                                    <p class="text-sm text-gray-600">Alle Dokumente pro Kunde übersichtlich</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-2xl p-12 text-center">
                    <h2 class="text-3xl font-bold text-white mb-4">Bereit, Ihre Kunden zu verwalten?</h2>
                    <p class="text-xl text-blue-100 mb-8">Starten Sie noch heute mit quickBill</p>
                    @auth
                        <a href="{{ route('customer.index') }}" class="inline-block bg-white text-blue-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all shadow-lg">
                            Zu den Kunden
                        </a>
                    @else
                        <a href="{{ route('contact.form') }}" class="inline-block bg-white text-blue-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all shadow-lg">
                            Angebot einholen
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <footer class="bg-gray-900 text-gray-300 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto text-center">
                <a href="{{ url('/') }}" class="inline-block mb-4">
                    <img src="{{ asset('logo/quickBill-Logo-alone.png') }}" alt="quickBill" class="h-8 w-auto brightness-0 invert mx-auto">
                </a>
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}. Alle Rechte vorbehalten.</p>
            </div>
        </footer>
    </body>
</html>

