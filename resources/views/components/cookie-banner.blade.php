<div id="cookie-banner" class="hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-2xl" x-data="cookieBanner()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <!-- Text -->
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Cookie-Einstellungen</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Wir verwenden Cookies, um Ihnen die bestmögliche Erfahrung auf unserer Website zu bieten. 
                    Sie können auswählen, welche Cookies Sie zulassen möchten. 
                    <a href="{{ route('cookies') }}" class="text-blue-900 hover:text-blue-800 underline">Mehr erfahren</a>
                </p>
                
                <!-- Cookie-Optionen -->
                <div class="space-y-3 mt-4">
                    <!-- Notwendige Cookies (immer aktiv) -->
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-900">Notwendige Cookies</label>
                            <p class="text-xs text-gray-500">Erforderlich für die Grundfunktionen der Website</p>
                        </div>
                        <div class="ml-4">
                            <input type="checkbox" checked disabled class="h-4 w-4 text-blue-900 focus:ring-blue-800 border-gray-300 rounded cursor-not-allowed">
                        </div>
                    </div>
                    
                    <!-- Funktionale Cookies -->
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-900">Funktionale Cookies</label>
                            <p class="text-xs text-gray-500">Ermöglichen erweiterte Funktionalität und Personalisierung</p>
                        </div>
                        <div class="ml-4">
                            <input type="checkbox" x-model="cookies.functional" class="h-4 w-4 text-blue-900 focus:ring-blue-800 border-gray-300 rounded cursor-pointer">
                        </div>
                    </div>
                    
                    <!-- Analytische Cookies -->
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-900">Analytische Cookies</label>
                            <p class="text-xs text-gray-500">Helfen uns, die Website zu verbessern</p>
                        </div>
                        <div class="ml-4">
                            <input type="checkbox" x-model="cookies.analytical" class="h-4 w-4 text-blue-900 focus:ring-blue-800 border-gray-300 rounded cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 lg:ml-6">
                <button 
                    @click="acceptAll()"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 text-white text-sm font-semibold rounded-lg hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300">
                    Alle akzeptieren
                </button>
                <button 
                    @click="acceptSelected()"
                    class="px-6 py-2.5 bg-white text-blue-900 text-sm font-semibold rounded-lg border-2 border-blue-900 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 transition-colors">
                    Auswahl speichern
                </button>
                <button 
                    @click="rejectAll()"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Nur notwendige
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cookieBanner() {
    return {
        cookies: {
            necessary: true, // Immer aktiv
            functional: false,
            analytical: false
        },
        
        init() {
            // Prüfe ob Cookie-Einstellungen bereits gespeichert wurden
            const saved = localStorage.getItem('cookiePreferences');
            if (saved) {
                const preferences = JSON.parse(saved);
                this.cookies = { ...this.cookies, ...preferences };
                // Banner nicht anzeigen, wenn bereits Einstellungen gespeichert wurden
                return;
            }
            
            // Banner nach kurzer Verzögerung anzeigen
            setTimeout(() => {
                const banner = document.getElementById('cookie-banner');
                if (banner) {
                    banner.classList.remove('hidden');
                }
            }, 1000);
        },
        
        acceptAll() {
            this.cookies.functional = true;
            this.cookies.analytical = true;
            this.savePreferences();
            this.hideBanner();
        },
        
        acceptSelected() {
            this.savePreferences();
            this.hideBanner();
        },
        
        rejectAll() {
            this.cookies.functional = false;
            this.cookies.analytical = false;
            this.savePreferences();
            this.hideBanner();
        },
        
        savePreferences() {
            localStorage.setItem('cookiePreferences', JSON.stringify(this.cookies));
            localStorage.setItem('cookiePreferencesDate', new Date().toISOString());
            
            // Event für andere Scripts, die auf Cookie-Einstellungen reagieren müssen
            window.dispatchEvent(new CustomEvent('cookiePreferencesUpdated', {
                detail: this.cookies
            }));
        },
        
        hideBanner() {
            const banner = document.getElementById('cookie-banner');
            if (banner) {
                banner.style.transition = 'transform 0.3s ease-out, opacity 0.3s ease-out';
                banner.style.transform = 'translateY(100%)';
                banner.style.opacity = '0';
                setTimeout(() => {
                    banner.classList.add('hidden');
                }, 300);
            }
        }
    }
}
</script>

