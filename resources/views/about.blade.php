<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $seoData = [
                'title' => 'Über uns - quickBill',
                'description' => 'Erfahren Sie mehr über quickBill - Ihre zuverlässige Lösung für professionelle Rechnungsverwaltung in Österreich.',
                'keywords' => 'Über uns, quickBill, Rechnungsverwaltung, Österreich, Unternehmen',
                'image' => asset('logo/quickBill-Logo-alone.png'),
            ];
            
            $structuredData = [
                \App\Helpers\SeoHelper::organizationStructuredData(),
            ];
        @endphp
        
        <title>{{ $seoData['title'] }}</title>
        
        {{-- SEO Meta-Tags --}}
        <x-seo-meta :seoData="$seoData" :canonicalUrl="url()->current()" :structuredData="$structuredData" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time() }}&t={{ time() }}">
        <link rel="alternate icon" type="image/svg+xml" href="{{ \App\Helpers\TemplateHelper::getFaviconPath() }}&t={{ time() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-white">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('logo/quickBill-Logo-alone.png') }}" alt="quickBill" class="h-10 w-auto">
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 font-medium">Anmelden</a>
                            <a href="{{ route('contact.form') }}" class="bg-blue-900 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-800 transition-colors">Angebot einholen</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Über <span class="text-blue-900">quickBill</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Wir sind Ihr zuverlässiger Partner für professionelle Rechnungsverwaltung in Österreich.
                </p>
            </div>
        </section>

        <!-- About Content -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-4xl mx-auto">
                <!-- Mission -->
                <div class="mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Unsere Mission</h2>
                    <p class="text-lg text-gray-700 leading-relaxed mb-4">
                        Bei quickBill glauben wir daran, dass Rechnungsverwaltung einfach, effizient und benutzerfreundlich sein sollte. Unsere Mission ist es, kleinen und mittleren Unternehmen in Österreich eine moderne, zuverlässige Lösung zu bieten, die ihnen hilft, Zeit zu sparen und ihre Geschäftsprozesse zu optimieren.
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Wir verstehen die Herausforderungen, vor denen Unternehmen heute stehen, und haben quickBill entwickelt, um genau diese zu bewältigen. Mit unserer Software können Sie sich auf das konzentrieren, was wirklich wichtig ist – Ihr Geschäft.
                    </p>
                </div>

                <!-- Values -->
                <div class="mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Unsere Werte</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-900 p-3 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Zuverlässigkeit</h3>
                            </div>
                            <p class="text-gray-700">
                                Wir setzen auf höchste Sicherheitsstandards und garantieren die Zuverlässigkeit unserer Plattform. Ihre Daten sind bei uns in sicheren Händen.
                            </p>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-900 p-3 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Einfachheit</h3>
                            </div>
                            <p class="text-gray-700">
                                Wir glauben an intuitive Benutzeroberflächen und einfache Prozesse. quickBill ist so konzipiert, dass jeder es sofort verwenden kann.
                            </p>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-900 p-3 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Kundennähe</h3>
                            </div>
                            <p class="text-gray-700">
                                Unser Fokus liegt auf unseren Kunden. Wir hören zu, lernen kontinuierlich dazu und entwickeln quickBill basierend auf Ihrem Feedback weiter.
                            </p>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-900 p-3 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Qualität</h3>
                            </div>
                            <p class="text-gray-700">
                                Wir setzen auf höchste Qualitätsstandards in allen Bereichen – von der Softwareentwicklung bis zum Kundenservice.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Why Austria -->
                <div class="mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Warum Österreich?</h2>
                    <p class="text-lg text-gray-700 leading-relaxed mb-4">
                        Als österreichisches Unternehmen verstehen wir die spezifischen Anforderungen und rechtlichen Rahmenbedingungen des österreichischen Marktes. quickBill ist speziell für österreichische Unternehmen entwickelt und berücksichtigt lokale Besonderheiten wie UID-Nummern, österreichische Steuervorschriften und die Anforderungen der österreichischen Wirtschaft.
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Wir sind stolz darauf, österreichische Unternehmen zu unterstützen und zur Digitalisierung der heimischen Wirtschaft beizutragen.
                    </p>
                </div>

                
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <img src="{{ asset('logo/quickBill-Logo-alone.png') }}" alt="quickBill" class="h-10 w-auto mb-4 brightness-0 invert">
                        <p class="text-sm text-gray-400">
                            Professionelle Rechnungsverwaltung für Ihr Unternehmen.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Produkt</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ url('/') }}#features" class="hover:text-white transition-colors">Features</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Unternehmen</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Über uns</a></li>
                            <li><a href="{{ route('contact.form') }}" class="hover:text-white transition-colors">Kontakt</a></li>
                            <li><a href="{{ route('impressum') }}" class="hover:text-white transition-colors">Impressum</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Rechtliches</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Datenschutz</a></li>
                            <li><a href="{{ route('cookies') }}" class="hover:text-white transition-colors">Cookies</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Alle Rechte vorbehalten.</p>
                </div>
            </div>
        </footer>

        <!-- Cookie Banner -->
        <x-cookie-banner />
    </body>
</html>

