<x-layout>
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Wartungsmodus-Verwaltung</h1>
            <p class="mt-2 text-sm text-gray-600">
                Versetzen Sie die Anwendung in den Wartungsmodus während Updates oder Wartungsarbeiten.
            </p>
        </div>

        <!-- Status Card -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if($isInMaintenance)
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-3 animate-pulse"></div>
                                <h2 class="text-lg font-semibold text-red-700">Wartungsmodus AKTIV</h2>
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <h2 class="text-lg font-semibold text-green-700">Anwendung ONLINE</h2>
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        @if($isInMaintenance)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Wartung läuft
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Verfügbar
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 text-sm text-gray-600">
                    @if($isInMaintenance)
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Die Anwendung ist derzeit nicht für Benutzer verfügbar. Nur autorisierte IPs können zugreifen.
                        </p>
                    @else
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Die Anwendung läuft normal und ist für alle Benutzer verfügbar.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Wartungsmodus aktivieren -->
            @if(!$isInMaintenance)
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Wartungsmodus aktivieren</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Versetzen Sie die Anwendung in den Wartungsmodus für Updates oder Wartung.
                    </p>
                </div>
                
                <form action="{{ route('maintenance.enable') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    
                    <!-- Nachricht -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">
                            Wartungsnachricht (optional)
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="3" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Die Anwendung wird gerade gewartet und ist bald wieder verfügbar..."
                        ></textarea>
                        <p class="mt-2 text-xs text-gray-500">Diese Nachricht wird Benutzern auf der Wartungsseite angezeigt.</p>
                    </div>

                    <!-- Erlaubte IPs -->
                    <div>
                        <label for="allowed_ips" class="block text-sm font-medium text-gray-700">
                            Erlaubte IP-Adressen (optional)
                        </label>
                        <input 
                            type="text" 
                            id="allowed_ips" 
                            name="allowed_ips" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="192.168.1.1, 10.0.0.1"
                        >
                        <p class="mt-2 text-xs text-gray-500">Kommagetrennte Liste von IPs, die trotz Wartungsmodus zugreifen können.</p>
                    </div>

                    <!-- Dauer -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">
                            Geschätzte Dauer (Minuten, optional)
                        </label>
                        <input 
                            type="number" 
                            id="duration" 
                            name="duration" 
                            min="1" 
                            max="1440"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="60"
                        >
                        <p class="mt-2 text-xs text-gray-500">Maximale Dauer: 24 Stunden (1440 Minuten).</p>
                    </div>

                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            onclick="return confirm('Sind Sie sicher, dass Sie die Anwendung in den Wartungsmodus versetzen möchten?')"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m0 0V9a2 2 0 012-2h0a2 2 0 012 2v6.01"></path>
                            </svg>
                            Wartungsmodus aktivieren
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Wartungsmodus deaktivieren -->
            @if($isInMaintenance)
            <div class="bg-white shadow-sm rounded-lg border border-red-200 border-2">
                <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                    <h3 class="text-lg font-medium text-red-900">Wartungsmodus deaktivieren</h3>
                    <p class="mt-1 text-sm text-red-700">
                        Stellen Sie die normale Funktionalität der Anwendung wieder her.
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium">Wartungsmodus ist aktiv</p>
                                <p>Benutzer können derzeit nicht auf die Anwendung zugreifen.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('maintenance.disable') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <button 
                            type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            onclick="return confirm('Sind Sie sicher, dass Sie den Wartungsmodus deaktivieren möchten?')"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Wartungsmodus deaktivieren
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Informationen und Terminal-Befehle -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Terminal-Befehle</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Alternative Verwaltung über die Kommandozeile.
                    </p>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Wartungsmodus aktivieren:</h4>
                        <code class="block w-full px-3 py-2 bg-gray-100 rounded-md text-sm text-gray-800 font-mono">
                            php artisan down
                        </code>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Mit benutzerdefinierter Nachricht:</h4>
                        <code class="block w-full px-3 py-2 bg-gray-100 rounded-md text-sm text-gray-800 font-mono">
                            php artisan down --message="Wartung läuft"
                        </code>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Mit erlaubten IPs:</h4>
                        <code class="block w-full px-3 py-2 bg-gray-100 rounded-md text-sm text-gray-800 font-mono">
                            php artisan down --allow=192.168.1.1
                        </code>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Wartungsmodus deaktivieren:</h4>
                        <code class="block w-full px-3 py-2 bg-gray-100 rounded-md text-sm text-gray-800 font-mono">
                            php artisan up
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout> 