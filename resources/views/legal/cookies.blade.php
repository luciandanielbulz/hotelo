<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $seoData = [
                'title' => 'Cookie-Richtlinie - quickBill',
                'description' => 'Informationen über die Verwendung von Cookies auf quickBill.',
                'keywords' => 'Cookies, Cookie-Richtlinie, quickBill',
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
                            <a href="{{ route('contact.form') }}" class="bg-blue-900 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-800 transition-colors">Jetzt Starten</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <section class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl font-bold text-gray-900 mb-8">Cookie-Richtlinie</h1>

                <div class="prose prose-lg max-w-none">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Was sind Cookies?</h2>
                    <p class="text-gray-700 mb-4">
                        Cookies sind kleine Textdateien, die auf Ihrem Endgerät (Computer, Tablet oder Mobilgerät) gespeichert werden, wenn Sie eine Website besuchen. Cookies werden häufig verwendet, um Websites effizienter zu machen und um Informationen bereitzustellen.
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Wie verwenden wir Cookies?</h2>
                    <p class="text-gray-700 mb-4">
                        quickBill verwendet Cookies, um die Funktionalität unserer Website zu gewährleisten und Ihre Erfahrung zu verbessern. Wir verwenden verschiedene Arten von Cookies:
                    </p>

                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">Notwendige Cookies</h3>
                    <p class="text-gray-700 mb-4">
                        Diese Cookies sind für das ordnungsgemäße Funktionieren der Website erforderlich. Sie ermöglichen grundlegende Funktionen wie Sicherheit, Netzwerkverwaltung und Zugänglichkeit. Sie können diese Cookies nicht deaktivieren.
                    </p>

                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">Funktionale Cookies</h3>
                    <p class="text-gray-700 mb-4">
                        Diese Cookies ermöglichen es der Website, erweiterte Funktionalität und Personalisierung bereitzustellen. Sie können von uns oder von Drittanbietern gesetzt werden, deren Dienste wir auf unseren Seiten hinzugefügt haben.
                    </p>

                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">Analytische Cookies</h3>
                    <p class="text-gray-700 mb-4">
                        Diese Cookies helfen uns zu verstehen, wie Besucher mit unserer Website interagieren, indem Informationen anonym gesammelt und gemeldet werden. Dies hilft uns, unsere Website zu verbessern.
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Cookie-Verwaltung</h2>
                    <p class="text-gray-700 mb-4">
                        Die meisten Browser akzeptieren Cookies automatisch. Sie können Ihren Browser jedoch so einstellen, dass er Cookies ablehnt oder Sie benachrichtigt, wenn Cookies gesendet werden. Bitte beachten Sie, dass das Deaktivieren von Cookies die Funktionalität dieser Website beeinträchtigen kann.
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Drittanbieter-Cookies</h2>
                    <p class="text-gray-700 mb-4">
                        Einige Cookies werden von Drittanbietern gesetzt, die auf unseren Seiten erscheinen. Wir haben keine Kontrolle über diese Cookies. Bitte besuchen Sie die Websites dieser Drittanbieter, um mehr über deren Cookie-Richtlinien zu erfahren.
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Änderungen dieser Cookie-Richtlinie</h2>
                    <p class="text-gray-700 mb-4">
                        Wir können diese Cookie-Richtlinie von Zeit zu Zeit aktualisieren. Wir empfehlen Ihnen, diese Seite regelmäßig zu überprüfen, um über Änderungen informiert zu bleiben.
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Kontakt</h2>
                    <p class="text-gray-700 mb-4">
                        Wenn Sie Fragen zu unserer Verwendung von Cookies haben, kontaktieren Sie uns bitte unter der im Impressum angegebenen Adresse.
                    </p>
                </div>
            </div>
        </section>

        <!-- Cookie-Einstellungen -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 sm:p-8 border border-stone-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Cookie-Einstellungen</h2>
                    <p class="text-sm text-gray-600 mb-6">
                        Sie können auswählen, welche Cookies Sie zulassen möchten. Notwendige Cookies können nicht deaktiviert werden, da sie für die Grundfunktionen der Website erforderlich sind.
                    </p>
                    
                    <div x-data="cookieSettings()" x-init="loadPreferences()">
                        <!-- Cookie-Optionen -->
                        <div class="space-y-4 mb-6">
                            <!-- Notwendige Cookies -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <label class="text-sm font-semibold text-gray-900 block mb-1">Notwendige Cookies</label>
                                        <p class="text-xs text-gray-500">Erforderlich für die Grundfunktionen der Website</p>
                                    </div>
                                    <div class="ml-4">
                                        <input type="checkbox" checked disabled class="h-5 w-5 text-blue-900 focus:ring-blue-800 border-gray-300 rounded cursor-not-allowed">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Funktionale Cookies -->
                            <div class="bg-white rounded-lg p-4 border border-stone-200 hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <label class="text-sm font-semibold text-gray-900 block mb-1">Funktionale Cookies</label>
                                        <p class="text-xs text-gray-500">Ermöglichen erweiterte Funktionalität und Personalisierung</p>
                                    </div>
                                    <div class="ml-4">
                                        <input type="checkbox" id="cookie-functional" x-model="cookies.functional" class="h-5 w-5 text-blue-900 focus:ring-blue-800 border-gray-300 rounded cursor-pointer">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Analytische Cookies -->
                            <div class="bg-white rounded-lg p-4 border border-stone-200 hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <label class="text-sm font-semibold text-gray-900 block mb-1">Analytische Cookies</label>
                                        <p class="text-xs text-gray-500">Helfen uns, die Website zu verbessern</p>
                                    </div>
                                    <div class="ml-4">
                                        <input type="checkbox" id="cookie-analytical" x-model="cookies.analytical" class="h-5 w-5 text-blue-900 focus:ring-blue-800 border-gray-300 rounded cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 justify-end">
                            <button 
                                @click="rejectAll()"
                                onclick="if(typeof Alpine === 'undefined') { handleRejectAllSettings(); }"
                                class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                                Nur notwendige
                            </button>
                            <button 
                                @click="acceptAll()"
                                onclick="if(typeof Alpine === 'undefined') { handleAcceptAllSettings(); }"
                                class="px-6 py-2.5 bg-white text-blue-900 text-sm font-medium rounded-lg border-2 border-blue-900 hover:bg-blue-50 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                                Alle akzeptieren
                            </button>
                            <button 
                                @click="savePreferences(); window.location.href = '{{ url("/") }}'"
                                onclick="if(typeof Alpine === 'undefined') { handleSavePreferences(); }"
                                class="px-6 py-2.5 bg-blue-900 text-white text-sm font-medium rounded-lg hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                                Auswahl speichern
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <x-public-footer />

        <script>
        // Fallback-Funktionen für den Fall, dass Alpine.js nicht geladen ist
        function handleSavePreferences() {
            const functionalCheckbox = document.getElementById('cookie-functional');
            const analyticalCheckbox = document.getElementById('cookie-analytical');
            
            const cookies = {
                necessary: true,
                functional: functionalCheckbox ? functionalCheckbox.checked : false,
                analytical: analyticalCheckbox ? analyticalCheckbox.checked : false
            };
            
            localStorage.setItem('cookiePreferences', JSON.stringify(cookies));
            localStorage.setItem('cookiePreferencesDate', new Date().toISOString());
            
            window.dispatchEvent(new CustomEvent('cookiePreferencesUpdated', {
                detail: cookies
            }));
            
            window.location.href = '{{ url("/") }}';
        }
        
        function handleAcceptAllSettings() {
            const functionalCheckbox = document.getElementById('cookie-functional');
            const analyticalCheckbox = document.getElementById('cookie-analytical');
            
            if (functionalCheckbox) functionalCheckbox.checked = true;
            if (analyticalCheckbox) analyticalCheckbox.checked = true;
            
            const cookies = {
                necessary: true,
                functional: true,
                analytical: true
            };
            localStorage.setItem('cookiePreferences', JSON.stringify(cookies));
            localStorage.setItem('cookiePreferencesDate', new Date().toISOString());
            
            window.dispatchEvent(new CustomEvent('cookiePreferencesUpdated', {
                detail: cookies
            }));
        }
        
        function handleRejectAllSettings() {
            const functionalCheckbox = document.getElementById('cookie-functional');
            const analyticalCheckbox = document.getElementById('cookie-analytical');
            
            if (functionalCheckbox) functionalCheckbox.checked = false;
            if (analyticalCheckbox) analyticalCheckbox.checked = false;
            
            const cookies = {
                necessary: true,
                functional: false,
                analytical: false
            };
            localStorage.setItem('cookiePreferences', JSON.stringify(cookies));
            localStorage.setItem('cookiePreferencesDate', new Date().toISOString());
            
            window.dispatchEvent(new CustomEvent('cookiePreferencesUpdated', {
                detail: cookies
            }));
        }
        
        function cookieSettings() {
            return {
                cookies: {
                    necessary: true,
                    functional: false,
                    analytical: false
                },
                
                loadPreferences() {
                    const saved = localStorage.getItem('cookiePreferences');
                    if (saved) {
                        const preferences = JSON.parse(saved);
                        this.cookies = { ...this.cookies, ...preferences };
                    }
                },
                
                acceptAll() {
                    this.cookies.functional = true;
                    this.cookies.analytical = true;
                    this.savePreferences();
                },
                
                rejectAll() {
                    this.cookies.functional = false;
                    this.cookies.analytical = false;
                    this.savePreferences();
                },
                
                savePreferences() {
                    localStorage.setItem('cookiePreferences', JSON.stringify(this.cookies));
                    localStorage.setItem('cookiePreferencesDate', new Date().toISOString());
                    
                    // Event für andere Scripts
                    window.dispatchEvent(new CustomEvent('cookiePreferencesUpdated', {
                        detail: this.cookies
                    }));
                }
            }
        }
        </script>
    </body>
</html>

