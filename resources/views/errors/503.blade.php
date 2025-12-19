<!DOCTYPE html>
<html lang="de" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wartungsmodus - {{ config('app.name', 'quickBill') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse-slow {
            animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-full bg-blue-50">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <!-- Logo/Icon -->
                <div class="mx-auto h-20 w-20 bg-indigo-100 rounded-full flex items-center justify-center animate-float">
                    <svg class="h-10 w-10 text-indigo-600 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>

                <!-- Haupttitel -->
                <h1 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Wartungsmodus
                </h1>
                
                <!-- Untertitel -->
                <p class="mt-2 text-sm text-gray-600">
                    {{ config('app.name', 'quickBill') }} wird gerade gewartet
                </p>
            </div>

            <!-- Nachricht -->
            <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200">
                <div class="text-center">
                    <div class="mb-4">
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Vorübergehend nicht verfügbar
                        </div>
                    </div>
                    
                    @php
                        $maintenanceMessage = 'Die Anwendung wird gerade gewartet und ist bald wieder verfügbar. Bitte versuchen Sie es in wenigen Minuten erneut.';
                        
                        // Versuche die Maintenance-Nachricht aus der Down-Datei zu lesen
                        $downFile = storage_path('framework/down');
                        if (file_exists($downFile)) {
                            $downData = json_decode(file_get_contents($downFile), true);
                            if (isset($downData['message']) && !empty($downData['message'])) {
                                $maintenanceMessage = $downData['message'];
                            }
                        }
                    @endphp
                    
                    <p class="text-gray-700 leading-relaxed mb-4">
                        {{ $maintenanceMessage }}
                    </p>
                    
                    <!-- Status-Indikator -->
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                            <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                            <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
                        </div>
                        <span>Wartung in Bearbeitung</span>
                    </div>
                </div>
            </div>

            <!-- Zusätzliche Informationen -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Was passiert gerade?</p>
                        <p>Wir führen wichtige Updates und Wartungsarbeiten durch, um Ihnen ein besseres Nutzererlebnis zu bieten. Die Anwendung wird in Kürze wieder verfügbar sein.</p>
                    </div>
                </div>
            </div>

            <!-- Retry Button -->
            <div class="text-center">
                <button 
                    onclick="window.location.reload()" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Neu laden
                </button>
            </div>

            <!-- Footer -->
            <div class="text-center text-xs text-gray-400">
                <p>{{ config('app.name', 'quickBill') }} &copy; {{ date('Y') }}</p>
                <p class="mt-1">Status: <span class="text-orange-600 font-medium">Wartung</span></p>
            </div>
        </div>
    </div>

    <!-- Auto-Refresh Script -->
    <script>
        // Automatisches Neuladen alle 30 Sekunden
        setTimeout(function() {
            window.location.reload();
        }, 30000);
        
        // Countdown-Anzeige
        let seconds = 30;
        const countdownInterval = setInterval(function() {
            seconds--;
            if (seconds <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    </script>
</body>
</html>
