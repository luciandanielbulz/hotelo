<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $seoData = [
                'title' => 'Preise - quickBill',
                'description' => 'Transparente Preise für quickBill - Ihre professionelle Rechnungsverwaltungslösung.',
                'keywords' => 'Preise, Pricing, quickBill, Rechnungsverwaltung, Kosten',
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
                            <img src="{{ asset('logo/quickBill HauptLogo2.png') }}" alt="quickBill" class="h-10 w-auto">
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 font-medium">Anmelden</a>
                            <a href="{{ route('pricing') }}" class="text-gray-700 hover:text-gray-900 font-medium">Preise</a>
                            <a href="{{ route('contact.form') }}" class="bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 text-white px-4 py-2 rounded-lg font-medium hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300">Jetzt Starten</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Pricing Section -->
        <section class="relative isolate bg-white px-6 pt-32 pb-24 sm:pt-40 sm:pb-32 lg:px-8">
            <div aria-hidden="true" class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="mx-auto aspect-[1155/678] w-[72.1875rem] bg-gradient-to-tr from-blue-400 to-blue-900 opacity-20"></div>
            </div>
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="text-base/7 font-semibold text-blue-900">Preise</h2>
                <p class="mt-2 text-balance text-5xl font-semibold tracking-tight text-gray-900 sm:text-6xl">Wählen Sie den richtigen Plan für Sie</p>
            </div>
            <p class="mx-auto mt-6 max-w-2xl text-pretty text-center text-lg font-medium text-gray-600 sm:text-xl/8">Wählen Sie einen passenden Plan, der mit den besten Features für Ihre Rechnungsverwaltung, Kundenbetreuung und Geschäftseffizienz ausgestattet ist.</p>
            <div class="mx-auto mt-16 grid max-w-lg grid-cols-1 items-center gap-y-6 sm:mt-20 sm:gap-y-0 lg:max-w-4xl lg:grid-cols-2">
                <div class="rounded-3xl rounded-t-3xl bg-white/60 p-8 ring-1 ring-gray-900/10 sm:mx-8 sm:rounded-b-none sm:p-10 lg:mx-0 lg:rounded-bl-3xl lg:rounded-tr-none">
                    <h3 id="tier-starter" class="text-base/7 font-semibold text-blue-900">Starter</h3>
                    <p class="mt-4 flex items-baseline gap-x-2">
                        <span class="text-5xl font-semibold tracking-tight text-gray-900">4,99€</span>
                        <span class="text-base text-gray-500">/Monat</span>
                    </p>
                    <p class="mt-2 text-sm text-gray-500">bei Jahreszahlung</p>
                    <p class="mt-6 text-base/7 text-gray-600">Der perfekte Plan, wenn Sie gerade erst mit unserer Rechnungsverwaltung starten.</p>
                    <ul role="list" class="mt-8 space-y-3 text-sm/6 text-gray-600 sm:mt-10">
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-900">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            Unbegrenzte Rechnungen
                        </li>
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-900">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            Kundenverwaltung
                        </li>
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-900">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            PDF-Export
                        </li>
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-900">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            E-Mail-Versand
                        </li>
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-900">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            Dashboard & Analysen
                        </li>
                    </ul>
                    <a href="{{ route('contact.form', ['plan' => 'starter']) }}" aria-describedby="tier-starter" class="mt-8 block rounded-md px-3.5 py-2.5 text-center text-sm font-semibold text-blue-900 ring-1 ring-inset ring-blue-200 hover:ring-blue-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900 sm:mt-10">Jetzt Starten</a>
                </div>
                <div class="relative rounded-3xl bg-gray-900 p-8 shadow-2xl ring-1 ring-gray-900/10 sm:p-10">
                    <h3 id="tier-enterprise" class="text-base/7 font-semibold text-blue-400">Enterprise</h3>
                    <p class="mt-4 flex items-baseline gap-x-2">
                        <span class="text-5xl font-semibold tracking-tight text-white">6,99€</span>
                        <span class="text-base text-gray-400">/Monat</span>
                    </p>
                    <p class="mt-2 text-sm text-gray-400">bei Jahreszahlung</p>
                    <p class="mt-6 text-base/7 text-gray-300">Dedizierter Support und maßgeschneiderte Infrastruktur für Ihr Unternehmen.</p>
                    <ul role="list" class="mt-8 space-y-3 text-sm/6 text-gray-300 sm:mt-10">
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-400">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            Alle Starter-Features
                        </li>
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-400">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            Reverse Charge (AT & DE)
                        </li>
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-400">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            Kleinunternehmerregelung
                        </li>
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-400">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            Automatische Updates
                        </li>
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-400">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            Dedizierter Support
                        </li>
                        <li class="flex gap-x-3">
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none text-blue-400">
                                <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            Individuelle Anpassungen
                        </li>
                    </ul>
                    <a href="{{ route('contact.form', ['plan' => 'enterprise']) }}" aria-describedby="tier-enterprise" class="mt-8 block rounded-md bg-blue-900 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900 sm:mt-10">Jetzt Starten</a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <x-public-footer />

        <!-- Cookie Banner -->
        <x-cookie-banner />
    </body>
</html>
