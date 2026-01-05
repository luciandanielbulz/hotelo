<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $seoData = [
                'title' => 'quickBill - Rechnungsverwaltung einfach gemacht',
                'description' => 'quickBill ist eine benutzerfreundliche Lösung zur mühelosen Verwaltung Ihrer Rechnungen. Erstellen, verfolgen und versenden Sie Rechnungen schnell und einfach - alles an einem Ort.',
                'keywords' => 'Rechnungsverwaltung, Rechnung erstellen, Rechnung verwalten, Rechnungssoftware, quickBill, KMU-Office, Venditio',
                'image' => asset('logo/quickBill-Logo-alone.png'),
            ];
            
            $structuredData = [
                \App\Helpers\SeoHelper::organizationStructuredData(),
                \App\Helpers\SeoHelper::structuredData([
                    'name' => 'quickBill',
                    'description' => $seoData['description'],
                    'url' => config('app.url'),
                ]),
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
                        <img src="{{ asset('logo/quickBill-Logo-alone.png') }}" alt="quickBill" class="h-10 w-auto">
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
        <section class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Sichere Lösungen für Ihre<br>
                        <span class="text-blue-900">Rechnungsverwaltung</span>
                    </h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-12 leading-relaxed">
                        quickBill bietet moderne Lösungen an, die Ihr Unternehmen bei der Verwaltung von Rechnungen und Angeboten unterstützt und Effizienz auf allen Ebenen gewährleistet.
                    </p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Branchenübergreifende Rechnungsverwaltung
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1: Rechnungen -->
                    <div class="bg-white overflow-hidden">
                        @php
                            $imagePath = 'images/features/rechnungen.jpg';
                            $imageExists = file_exists(public_path($imagePath)) || file_exists(public_path('images/features/rechnungen.png'));
                        @endphp
                        @if($imageExists)
                            <div class="mb-6 overflow-hidden">
                                <img src="{{ asset(file_exists(public_path('images/features/rechnungen.jpg')) ? 'images/features/rechnungen.jpg' : 'images/features/rechnungen.png') }}" 
                                     alt="Rechnungen verwalten" 
                                     class="w-full h-auto object-cover">
                            </div>
                        @else
                            <div class="h-64 bg-blue-50 flex items-center justify-center mb-6">
                                <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Rechnungen verwalten</h3>
                            <p class="text-gray-600 leading-relaxed mb-4">
                                Erstellen, verwalten und versenden Sie Rechnungen schnell und einfach. Automatische Nummerierung und professionelle PDF-Exporte.
                            </p>
                            <a href="{{ route('features.invoices') }}" class="inline-flex items-center text-blue-900 font-medium hover:text-blue-800">
                                Mehr erfahren
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Feature 2: Angebote -->
                    <div class="bg-white overflow-hidden">
                        @php
                            $imagePath = 'images/features/angebote.jpg';
                            $imageExists = file_exists(public_path($imagePath)) || file_exists(public_path('images/features/angebote.png'));
                        @endphp
                        @if($imageExists)
                            <div class="mb-6 overflow-hidden">
                                <img src="{{ asset(file_exists(public_path('images/features/angebote.jpg')) ? 'images/features/angebote.jpg' : 'images/features/angebote.png') }}" 
                                     alt="Angebote erstellen" 
                                     class="w-full h-auto object-cover">
                            </div>
                        @else
                            <div class="h-64 bg-blue-50 flex items-center justify-center mb-6">
                                <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Angebote erstellen</h3>
                            <p class="text-gray-600 leading-relaxed mb-4">
                                Professionelle Angebote erstellen und verwalten. Von der Erstellung bis zur Archivierung – alles an einem Ort.
                            </p>
                            <a href="{{ route('features.offers') }}" class="inline-flex items-center text-blue-900 font-medium hover:text-blue-800">
                                Mehr erfahren
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Feature 3: Kundenverwaltung -->
                    <div class="bg-white overflow-hidden">
                        @php
                            $imagePath = 'images/features/kundenverwaltung.jpg';
                            $imageExists = file_exists(public_path($imagePath)) || file_exists(public_path('images/features/kundenverwaltung.png'));
                        @endphp
                        @if($imageExists)
                            <div class="mb-6 overflow-hidden">
                                <img src="{{ asset(file_exists(public_path('images/features/kundenverwaltung.jpg')) ? 'images/features/kundenverwaltung.jpg' : 'images/features/kundenverwaltung.png') }}" 
                                     alt="Kundenverwaltung" 
                                     class="w-full h-auto object-cover">
                            </div>
                        @else
                            <div class="h-64 bg-blue-50 flex items-center justify-center mb-6">
                                <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Kundenverwaltung</h3>
                            <p class="text-gray-600 leading-relaxed mb-4">
                                Verwalten Sie alle Ihre Kunden zentral. Kontaktdaten, Historie und alle zugehörigen Dokumente übersichtlich organisiert.
                            </p>
                            <a href="{{ route('features.customers') }}" class="inline-flex items-center text-blue-900 font-medium hover:text-blue-800">
                                Mehr erfahren
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Feature 4: PDF-Export -->
                    <div class="bg-white overflow-hidden">
                        @php
                            $imagePath = 'images/features/pdfs.jpg';
                            $imageExists = file_exists(public_path($imagePath)) || file_exists(public_path('images/features/pdfs.png'));
                        @endphp
                        @if($imageExists)
                            <div class="mb-6 overflow-hidden">
                                <img src="{{ asset(file_exists(public_path('images/features/pdfs.jpg')) ? 'images/features/pdfs.jpg' : 'images/features/pdfs.png') }}" 
                                     alt="Professionelle PDFs" 
                                     class="w-full h-auto object-cover">
                            </div>
                        @else
                            <div class="h-64 bg-blue-50 flex items-center justify-center mb-6">
                                <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Professionelle PDFs</h3>
                            <p class="text-gray-600 leading-relaxed mb-4">
                                Erstellen Sie professionelle PDF-Dokumente mit Ihrem individuellen Logo und Layout. Direkt versendbar per E-Mail.
                            </p>
                            <a href="{{ route('features.pdfs') }}" class="inline-flex items-center text-blue-900 font-medium hover:text-blue-800">
                                Mehr erfahren
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Feature 5: Direkter Versand -->
                    <div class="bg-white overflow-hidden">
                        @php
                            $imagePath = 'images/features/versand.jpg';
                            $imageExists = file_exists(public_path($imagePath)) || file_exists(public_path('images/features/versand.png'));
                        @endphp
                        @if($imageExists)
                            <div class="mb-6 overflow-hidden">
                                <img src="{{ asset(file_exists(public_path('images/features/versand.jpg')) ? 'images/features/versand.jpg' : 'images/features/versand.png') }}" 
                                     alt="Direkter Versand" 
                                     class="w-full h-auto object-cover">
                            </div>
                        @else
                            <div class="h-64 bg-blue-50 flex items-center justify-center mb-6">
                                <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Direkter Versand</h3>
                            <p class="text-gray-600 leading-relaxed mb-4">
                                Versenden Sie Rechnungen und Angebote direkt aus der App per E-Mail. Schnell, sicher und ohne zusätzliche Tools.
                            </p>
                            <a href="{{ route('features.sending') }}" class="inline-flex items-center text-blue-900 font-medium hover:text-blue-800">
                                Mehr erfahren
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Feature 6: Übersicht & Analyse -->
                    <div class="bg-white overflow-hidden">
                        @php
                            $imagePath = 'images/features/analysen.jpg';
                            $imageExists = file_exists(public_path($imagePath)) || file_exists(public_path('images/features/analysen.png'));
                        @endphp
                        @if($imageExists)
                            <div class="mb-6 overflow-hidden">
                                <img src="{{ asset(file_exists(public_path('images/features/analysen.jpg')) ? 'images/features/analysen.jpg' : 'images/features/analysen.png') }}" 
                                     alt="Übersicht & Analyse" 
                                     class="w-full h-auto object-cover">
                            </div>
                        @else
                            <div class="h-64 bg-blue-50 flex items-center justify-center mb-6">
                                <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Übersicht & Analyse</h3>
                            <p class="text-gray-600 leading-relaxed mb-4">
                                Behalten Sie den Überblick mit detaillierten Dashboards und Analysen. Verfolgen Sie Umsätze, offene Posten und mehr.
                            </p>
                            <a href="{{ route('features.analytics') }}" class="inline-flex items-center text-blue-900 font-medium hover:text-blue-800">
                                Mehr erfahren
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8">
                            Über quickBill
                        </h2>
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    Was macht quickBill einzigartig?
                                </h3>
                                <p class="text-gray-600 leading-relaxed">
                                    quickBill kombiniert moderne Rechnungsverwaltung mit individueller Anpassung. Statt Standardlösungen erhalten Unternehmen maßgeschneiderte Konzepte, die exakt auf ihre Anforderungen abgestimmt sind.
                                </p>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    Warum sollten Unternehmen sich für quickBill entscheiden?
                                </h3>
                                <p class="text-gray-600 leading-relaxed">
                                    quickBill setzt auf innovative Technologien, höchste Zuverlässigkeit und effiziente Prozesse. Dadurch werden Rechnungsprozesse automatisiert und Fehler nachhaltig minimiert – für maximale Effizienz auf allen Ebenen.
                                </p>
                                </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    Wie hebt sich quickBill von anderen Anbietern ab?
                                </h3>
                                <p class="text-gray-600 leading-relaxed">
                                    Im Gegensatz zu vielen Anbietern verfolgt quickBill einen ganzheitlichen Ansatz. Rechnungserstellung, Kundenverwaltung, Dokumentation und kontinuierliche Optimierung greifen nahtlos ineinander und sorgen für langfristigen Erfolg.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Welche Produkte und Lösungen bietet quickBill an?</h3>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            quickBill ist mehr als eine Rechnungssoftware – wir bieten eine ganzheitliche Lösung für Ihre Finanzverwaltung. Mit direktem E-Mail-Versand, professionellen PDF-Exporten und umfassender Kundenverwaltung vereint quickBill moderne Technologie, skalierbare Funktionen und effiziente Prozesse in einem ganzheitlichen Ansatz.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Rechnungen und Angebote erstellen</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Zentrale Kundenverwaltung</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Professionelle PDF-Exporte</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Direkter E-Mail-Versand</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                                <span class="text-gray-700">Übersichtliche Dashboards</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-900 to-blue-800">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                    Bereit, Ihre Rechnungsverwaltung zu optimieren?
                </h2>
                <p class="text-xl text-blue-100 mb-8">
                    Starten Sie noch heute und erleben Sie, wie einfach Rechnungsverwaltung sein kann.
                </p>
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block bg-white text-blue-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all shadow-lg">
                        Zum Dashboard
                    </a>
                @else
                    <a href="{{ route('contact.form') }}" class="inline-block bg-white text-blue-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all shadow-lg">
                        Angebot einholen
                    </a>
                @endauth
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
                            <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Preise</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Dokumentation</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Unternehmen</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors">Über uns</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Kontakt</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Impressum</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Rechtliches</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors">Datenschutz</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">AGB</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Alle Rechte vorbehalten.</p>
            </div>
        </div>
        </footer>
    </body>
</html>
