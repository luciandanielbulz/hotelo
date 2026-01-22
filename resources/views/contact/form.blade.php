<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $seoData = [
                'title' => 'Verbindlich bestellen - quickBill',
                'description' => 'Bestellen Sie jetzt quickBill und starten Sie mit Ihrer professionellen Rechnungsverwaltung.',
                'keywords' => 'Bestellung, Bestellen, quickBill, Rechnungsverwaltung',
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
        
        <!-- Google reCAPTCHA v3 -->
        @if(config('services.recaptcha.site_key'))
            <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
        @endif
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
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 font-medium">Anmelden</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-white">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Verbindlich<br>
                    <span class="text-blue-900">bestellen</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8 leading-relaxed">
                    Bestellen Sie jetzt quickBill und starten Sie mit Ihrer professionellen Rechnungsverwaltung. Wir finden die perfekte Lösung für Ihre Anforderungen.
                </p>
            </div>
        </section>

        <!-- Contact Form Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 md:p-12">
                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Es sind Fehler aufgetreten:</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Name <span class="text-blue-900">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       required
                                       value="{{ old('name') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-900 transition-colors"
                                       placeholder="Ihr Name">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    E-Mail <span class="text-blue-900">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       required
                                       value="{{ old('email') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-900 transition-colors"
                                       placeholder="ihre@email.de">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-2">
                                    Firma
                                </label>
                                <input type="text" 
                                       name="company" 
                                       id="company"
                                       value="{{ old('company') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-900 transition-colors"
                                       placeholder="Ihre Firma">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Telefon
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone"
                                       value="{{ old('phone') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-900 transition-colors"
                                       placeholder="+43 1 234 567 89">
                            </div>
                        </div>

                        <!-- Adressfelder -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Adresse</h3>
                            
                            <div class="mb-6">
                                <label for="street" class="block text-sm font-medium text-gray-700 mb-2">
                                    Straße und Hausnummer <span class="text-blue-900">*</span>
                                </label>
                                <input type="text" 
                                       name="street" 
                                       id="street" 
                                       required
                                       value="{{ old('street') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-900 transition-colors"
                                       placeholder="Musterstraße 123">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                        PLZ <span class="text-blue-900">*</span>
                                    </label>
                                    <input type="text" 
                                           name="postal_code" 
                                           id="postal_code" 
                                           required
                                           value="{{ old('postal_code') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-900 transition-colors"
                                           placeholder="12345">
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ort <span class="text-blue-900">*</span>
                                    </label>
                                    <input type="text" 
                                           name="city" 
                                           id="city" 
                                           required
                                           value="{{ old('city') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-900 transition-colors"
                                           placeholder="Wien">
                                </div>

                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                        Land
                                    </label>
                                    <input type="text" 
                                           name="country_display" 
                                           id="country_display" 
                                           value="Österreich"
                                           readonly
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                                    <input type="hidden" name="country" value="AT">
                                </div>
                            </div>
                        </div>

                        <!-- Weitere Informationen -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Weitere Informationen</h3>
                            
                            <div class="mb-6">
                                <label for="uid_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    UID-Nummer / Steuernummer
                                </label>
                                <input type="text" 
                                       name="uid_number" 
                                       id="uid_number"
                                       value="{{ old('uid_number') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-900 transition-colors"
                                       placeholder="ATU12345678">
                            </div>

                            @if(isset($selectedPlan) && $selectedPlan)
                                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Gewählter Plan
                                    </label>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-lg font-semibold text-blue-900">{{ $planName }}</span>
                                            <span class="text-sm text-gray-600 ml-2">({{ $planPrice }})</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="plan" value="{{ $selectedPlan }}">
                                </div>
                            @endif

                            <div class="flex items-start mb-6">
                                <input type="checkbox" 
                                       name="is_kleinunternehmer" 
                                       id="is_kleinunternehmer"
                                       value="1"
                                       {{ old('is_kleinunternehmer') ? 'checked' : '' }}
                                       class="mt-1 h-4 w-4 text-blue-900 focus:ring-blue-700 border-gray-300 rounded">
                                <label for="is_kleinunternehmer" class="ml-3 text-sm text-gray-600">
                                    Ich bin Kleinunternehmer gemäß §19 UStG
                                </label>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Ihre Nachricht / Anmerkungen
                            </label>
                            <textarea name="message" 
                                      id="message" 
                                      rows="4" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-900 transition-colors resize-none"
                                      placeholder="Zusätzliche Informationen oder Fragen...">{{ old('message') }}</textarea>
                            <p class="mt-2 text-sm text-gray-500">Optional: Geben Sie hier zusätzliche Informationen oder Fragen an.</p>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" 
                                   name="privacy" 
                                   id="privacy" 
                                   required
                                   class="mt-1 h-4 w-4 text-blue-900 focus:ring-blue-700 border-gray-300 rounded">
                            <label for="privacy" class="ml-3 text-sm text-gray-600">
                                Ich habe die <a href="{{ route('privacy') }}" class="text-blue-900 hover:text-blue-800 underline">Datenschutzerklärung</a> gelesen und stimme zu. <span class="text-blue-900">*</span>
                            </label>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" 
                                   name="binding_order" 
                                   id="binding_order" 
                                   required
                                   class="mt-1 h-4 w-4 text-blue-900 focus:ring-blue-700 border-gray-300 rounded">
                            <label for="binding_order" class="ml-3 text-sm text-gray-600">
                                Ich bestelle verbindlich. <span class="text-blue-900">*</span>
                            </label>
                        </div>

                        @if(config('services.recaptcha.site_key'))
                            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                        @endif

                        <div>
                            <button type="submit" 
                                    id="submit-button"
                                    class="w-full bg-blue-900 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-800 transition-all shadow-lg hover:shadow-xl">
                                Verbindlich bestellen
                            </button>
                        </div>
                    </form>
                    
                    @if(config('services.recaptcha.site_key'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.querySelector('form');
                                const submitButton = document.getElementById('submit-button');
                                
                                form.addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    
                                    grecaptcha.ready(function() {
                                        grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'submit'}).then(function(token) {
                                            document.getElementById('g-recaptcha-response').value = token;
                                            form.submit();
                                        });
                                    });
                                });
                            });
                        </script>
                    @endif
                </div>

                <!-- Additional Info -->
                <div class="mt-12 text-center">
                    <p class="text-gray-600 mb-4">Oder kontaktieren Sie uns direkt:</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="mailto:info@quickbill.at" class="flex items-center text-gray-700 hover:text-blue-900 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            info@quickbill.at
                        </a>
                        <span class="hidden sm:inline text-gray-400">|</span>
                        <a href="tel:+43123456789" class="flex items-center text-gray-700 hover:text-blue-900 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +43 1 234 567 89
                        </a>
                    </div>
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
        <!-- Cookie Banner -->
        <x-cookie-banner />
    </body>
</html>

