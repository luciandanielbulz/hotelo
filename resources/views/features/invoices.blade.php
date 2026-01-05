<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $seoData = [
                'title' => 'Rechnungen verwalten - quickBill',
                'description' => 'Erstellen, verwalten und versenden Sie Rechnungen schnell und einfach mit quickBill. Automatische Nummerierung und professionelle PDF-Exporte.',
                'keywords' => 'Rechnungen erstellen, Rechnung verwalten, Rechnung versenden, Rechnungssoftware',
                'image' => asset('logo/quickBill-Logo-alone.png'),
            ];
            
            $structuredData = [
                \App\Helpers\SeoHelper::organizationStructuredData(),
            ];
        @endphp
        
        <title>{{ $seoData['title'] }}</title>
        <x-seo-meta :seoData="$seoData" :canonicalUrl="url()->current()" :structuredData="$structuredData" />

        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time() }}&t={{ time() }}">
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
                        <a href="{{ url('/') }}" class="text-gray-700 hover:text-gray-900 font-medium">Zurück zur Startseite</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('contact.form') }}" class="bg-blue-900 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-800 transition-colors">Angebot einholen</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 rounded-xl mb-6">
                        <svg class="w-10 h-10 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Rechnungen<br>
                        <span class="text-blue-900">professionell verwalten</span>
                    </h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                        Erstellen, verwalten und versenden Sie Rechnungen schnell und einfach. Mit automatischer Nummerierung, professionellen PDF-Exporten und zentraler Verwaltung.
                    </p>
                </div>
            </div>
        </section>

        <!-- Features Detail Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Schnelle Rechnungserstellung</h2>
                        <p class="text-lg text-gray-600 mb-4 leading-relaxed">
                            Erstellen Sie Rechnungen in wenigen Minuten. Mit vorkonfigurierten Vorlagen, automatischer Kundenauswahl und intelligenten Vorschlägen sparen Sie wertvolle Zeit.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Vorkonfigurierte Rechnungsvorlagen</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Automatische Kunden- und Produktauswahl</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Kopieren von bestehenden Rechnungen</span>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-base font-medium text-gray-900">Zeitersparnis</h4>
                                    <p class="text-sm text-gray-600">Bis zu 70% weniger Zeit für die Rechnungserstellung</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100">
                                        <svg class="h-6 w-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-base font-medium text-gray-900">Weniger Fehler</h4>
                                    <p class="text-sm text-gray-600">Automatische Berechnungen reduzieren Fehlerquellen</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Automatische Nummerierung</h3>
                        <p class="text-gray-600 text-sm">Individuelle Nummernformate und Präfixe für jede Rechnung</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Direkter Versand</h3>
                        <p class="text-gray-600 text-sm">Rechnungen direkt per E-Mail versenden</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">PDF-Export</h3>
                        <p class="text-gray-600 text-sm">Professionelle PDFs mit individuellem Logo</p>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-2xl p-12 text-center">
                    <h2 class="text-3xl font-bold text-white mb-4">Bereit, Ihre Rechnungen zu verwalten?</h2>
                    <p class="text-xl text-blue-100 mb-8">Starten Sie noch heute mit quickBill</p>
                    @auth
                        <a href="{{ route('invoice.index') }}" class="inline-block bg-white text-blue-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all shadow-lg">
                            Zu den Rechnungen
                        </a>
                    @else
                        <a href="{{ route('contact.form') }}" class="inline-block bg-white text-blue-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all shadow-lg">
                            Angebot einholen
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Footer -->
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

