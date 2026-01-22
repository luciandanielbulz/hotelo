<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $seoData = [
                'title' => 'Datenschutzerklärung - quickBill',
                'description' => 'Datenschutzerklärung von quickBill gemäß DSGVO.',
                'keywords' => 'Datenschutz, DSGVO, quickBill, Datenschutzerklärung',
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
                <h1 class="text-4xl font-bold text-gray-900 mb-8">Datenschutzerklärung</h1>

                <div class="prose prose-lg max-w-none">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Datenschutz auf einen Blick</h2>
                    
                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">Allgemeine Hinweise</h3>
                    <p class="text-gray-700 mb-4">
                        Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten passiert, wenn Sie diese Website besuchen. Personenbezogene Daten sind alle Daten, mit denen Sie persönlich identifiziert werden können.
                    </p>

                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">Datenerfassung auf dieser Website</h3>
                    <p class="text-gray-700 mb-4">
                        <strong>Wer ist verantwortlich für die Datenerfassung auf dieser Website?</strong><br>
                        Die Datenverarbeitung auf dieser Website erfolgt durch den Websitebetreiber. Dessen Kontaktdaten können Sie dem Abschnitt „Hinweis zur Verantwortlichen Stelle" in dieser Datenschutzerklärung entnehmen.
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. Hosting</h2>
                    <p class="text-gray-700 mb-4">
                        Diese Website wird bei [Hosting-Anbieter] gehostet. Anbieter ist [Name des Hosting-Anbieters]. Wenn Sie diese Website besuchen, erfasst [Hosting-Anbieter] verschiedene Logfiles inklusive Ihrer IP-Adressen.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Details entnehmen Sie der Datenschutzerklärung von [Hosting-Anbieter]: [Link zur Datenschutzerklärung]
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">3. Allgemeine Hinweise und Pflichtinformationen</h2>
                    
                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">Datenschutz</h3>
                    <p class="text-gray-700 mb-4">
                        Die Betreiber dieser Seiten nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Wir behandeln Ihre personenbezogenen Daten vertraulich und entsprechend den gesetzlichen Datenschutzbestimmungen sowie dieser Datenschutzerklärung.
                    </p>

                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">Hinweis zur verantwortlichen Stelle</h3>
                    <p class="text-gray-700 mb-4">
                        Die verantwortliche Stelle für die Datenverarbeitung auf dieser Website ist:<br><br>
                        <strong>quickBill</strong><br>
                        Ing. Lucian-Daniel Bulz<br>
                        Neue-Welt-Gasse 3<br>
                        8600 Bruck an der Mur<br>
                        Österreich<br><br>
                        E-Mail: office@quickbill.at
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">4. Datenerfassung auf dieser Website</h2>
                    
                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">Kontaktformular</h3>
                    <p class="text-gray-700 mb-4">
                        Wenn Sie uns per Kontaktformular Anfragen zukommen lassen, werden Ihre Angaben aus dem Anfrageformular inklusive der von Ihnen dort angegebenen Kontaktdaten zwecks Bearbeitung der Anfrage und für den Fall von Anschlussfragen bei uns gespeichert. Diese Daten geben wir nicht ohne Ihre Einwilligung weiter.
                    </p>

                    <h3 class="text-xl font-bold text-gray-900 mt-6 mb-3">Anmeldedaten</h3>
                    <p class="text-gray-700 mb-4">
                        Wenn Sie sich für einen Account registrieren, speichern wir Ihre Anmeldedaten (E-Mail-Adresse, Name) zur Verwaltung Ihres Accounts. Diese Daten werden nur für die Bereitstellung unserer Dienste verwendet und nicht an Dritte weitergegeben.
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">5. Ihre Rechte</h2>
                    <p class="text-gray-700 mb-4">
                        Sie haben jederzeit das Recht, Auskunft über Ihre bei uns gespeicherten personenbezogenen Daten zu erhalten. Außerdem haben Sie das Recht auf Berichtigung, Löschung oder Einschränkung der Verarbeitung Ihrer Daten sowie das Recht auf Datenübertragbarkeit.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Bei Fragen zum Datenschutz können Sie sich jederzeit an uns wenden unter der im Impressum angegebenen Adresse.
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">6. SSL- bzw. TLS-Verschlüsselung</h2>
                    <p class="text-gray-700 mb-4">
                        Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der Übertragung vertraulicher Inhalte, wie zum Beispiel Bestellungen oder Anfragen, die Sie an uns als Seitenbetreiber senden, eine SSL- bzw. TLS-Verschlüsselung. Eine verschlüsselte Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von "http://" auf "https://" wechselt und an dem Schloss-Symbol in Ihrer Browserzeile.
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <x-public-footer />

        <!-- Cookie Banner -->
        <x-cookie-banner />
    </body>
</html>

