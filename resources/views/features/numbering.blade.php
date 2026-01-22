<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $seoData = [
                'title' => 'Automatische Nummerierung - quickBill',
                'description' => 'Individuelle Nummernformate und Präfixe für Rechnungen und Angebote. Vollständig konfigurierbar nach Ihren Bedürfnissen.',
                'keywords' => 'Rechnungsnummer, Angebotsnummer, automatische Nummerierung',
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
                            <a href="{{ route('contact.form') }}" class="bg-blue-900 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-800 transition-colors">Jetzt Starten</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <section class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-white">
            <div class="max-w-7xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 rounded-xl mb-6">
                    <svg class="w-10 h-10 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Automatische<br>
                    <span class="text-blue-900">Nummerierung</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                    Individuelle Nummernformate und Präfixe für Rechnungen und Angebote. Vollständig konfigurierbar nach Ihren Bedürfnissen.
                </p>
            </div>
        </section>

        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Flexible Nummernformate</h2>
                        <p class="text-lg text-gray-600 mb-4 leading-relaxed">
                            Konfigurieren Sie Ihre Nummernformate genau nach Ihren Anforderungen. Mit Präfixen, Jahreszahlen und fortlaufenden Nummern.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Individuelle Präfixe (z.B. RE-2024-)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Automatische Jahreszahlen</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Fortlaufende Nummern mit führenden Nullen</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-8 border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Beispielformate</h3>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm text-gray-500 mb-1">Rechnung</div>
                                <div class="text-lg font-mono font-semibold text-gray-900">RE-2024-0001</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm text-gray-500 mb-1">Angebot</div>
                                <div class="text-lg font-mono font-semibold text-gray-900">ANG-2024-0001</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm text-gray-500 mb-1">Individuell</div>
                                <div class="text-lg font-mono font-semibold text-gray-900">IHR-PRÄFIX-2024-001</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-2xl p-12 text-center">
                    <h2 class="text-3xl font-bold text-white mb-4">Bereit, Ihre Nummerierung zu konfigurieren?</h2>
                    <p class="text-xl text-blue-100 mb-8">Starten Sie noch heute mit quickBill</p>
                    @auth
                        <a href="{{ route('clients.my-settings') }}" class="inline-block bg-white text-blue-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all shadow-lg">
                            Zu den Einstellungen
                        </a>
                    @else
                        <a href="{{ route('contact.form') }}" class="inline-block bg-white text-blue-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all shadow-lg">
                            Jetzt Starten
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

