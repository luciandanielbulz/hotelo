<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $seoData = [
                'title' => 'Vielen Dank - quickBill',
                'description' => 'Vielen Dank für Ihre Anfrage. Wir werden uns in Kürze bei Ihnen melden.',
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
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('logo/quickBill-Logo-alone.png') }}" alt="quickBill" class="h-10 w-auto">
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/') }}" class="text-gray-700 hover:text-gray-900 font-medium">Zurück zur Startseite</a>
                    </div>
                </div>
            </div>
        </nav>

        <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 min-h-screen flex items-center bg-gradient-to-br from-gray-50 to-white">
            <div class="max-w-2xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 rounded-full mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-6">
                    Vielen Dank für Ihre Anfrage!
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Wir haben Ihre Anfrage erhalten und werden uns in Kürze bei Ihnen melden, um Ihnen ein individuelles Angebot zu erstellen.
                </p>
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Was passiert als Nächstes?</h2>
                    <div class="space-y-4 text-left">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-600 font-semibold">1</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-base font-medium text-gray-900">Anfrage erhalten</h3>
                                <p class="text-sm text-gray-600">Wir haben Ihre Anfrage erhalten und werden sie schnellstmöglich bearbeiten.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-600 font-semibold">2</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-base font-medium text-gray-900">Kontaktaufnahme</h3>
                                <p class="text-sm text-gray-600">Unser Team wird sich innerhalb von 24 Stunden bei Ihnen melden.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-600 font-semibold">3</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-base font-medium text-gray-900">Individuelles Angebot</h3>
                                <p class="text-sm text-gray-600">Wir erstellen Ihnen ein maßgeschneidertes Angebot basierend auf Ihren Anforderungen.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ url('/') }}" class="inline-block bg-red-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-red-700 transition-all shadow-lg">
                        Zurück zur Startseite
                    </a>
                    <a href="{{ route('contact.form') }}" class="inline-block bg-white text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg border-2 border-gray-300 hover:border-gray-400 transition-all">
                        Weitere Anfrage stellen
                    </a>
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
        <!-- Cookie Banner -->
        <x-cookie-banner />
    </body>
</html>

