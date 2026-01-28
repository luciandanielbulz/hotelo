<!-- Cookie Banner (unten) -->
<div id="cookie-dialog-overlay" 
     class="fixed bottom-0 left-0 right-0 z-[9999] bg-white border-t border-gray-200 shadow-lg"
     style="display: none;"
     x-data="cookieBanner()" 
     x-init="init()"
     x-show="showDialog"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="translate-y-full"
     x-transition:enter-end="translate-y-0"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="translate-y-0"
     x-transition:leave-end="translate-y-full">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Text -->
            <div class="flex-1 text-sm text-gray-600">
                <p>
                    Wir verwenden Cookies, um Ihnen die bestmögliche Erfahrung zu bieten. 
                    <a href="{{ route('cookies') }}" class="text-blue-900 hover:text-blue-800 underline">Mehr erfahren</a>
                </p>
            </div>
            
            <!-- Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('cookies') }}"
                   @click="hideDialog()"
                   class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Cookies anpassen
                </a>
                <button 
                    @click="rejectAll()"
                    onclick="if(typeof Alpine === 'undefined') { handleRejectAll(); }"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Ablehnen
                </button>
                <button 
                    @click="acceptAll()"
                    onclick="if(typeof Alpine === 'undefined') { handleAcceptAll(); }"
                    class="px-5 py-2 bg-blue-900 text-white text-sm font-medium rounded-lg hover:bg-blue-800 transition-colors">
                    Akzeptieren
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Fallback-Funktionen für den Fall, dass Alpine.js nicht geladen ist
function handleAcceptAll() {
    const cookies = {
        necessary: true,
        functional: true,
        analytical: true
    };
    localStorage.setItem('cookiePreferences', JSON.stringify(cookies));
    localStorage.setItem('cookiePreferencesDate', new Date().toISOString());
    showToastMessage('Alle Cookies wurden akzeptiert.');
    hideCookieBanner();
}

function handleRejectAll() {
    const cookies = {
        necessary: true,
        functional: false,
        analytical: false
    };
    localStorage.setItem('cookiePreferences', JSON.stringify(cookies));
    localStorage.setItem('cookiePreferencesDate', new Date().toISOString());
    hideCookieBanner();
}

function showToastMessage(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 z-[10000] bg-green-50 border border-green-200 text-green-900 px-4 py-3 rounded-lg shadow-lg text-sm font-medium';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transition = 'opacity 0.3s ease-out';
        toast.style.opacity = '0';
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

function hideCookieBanner() {
    const overlay = document.getElementById('cookie-dialog-overlay');
    if (overlay) {
        overlay.style.transition = 'transform 0.3s ease-out, opacity 0.3s ease-out';
        overlay.style.transform = 'translateY(100%)';
        overlay.style.opacity = '0';
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
}

// Fallback für den Fall, dass Alpine.js nicht geladen ist
document.addEventListener('DOMContentLoaded', function() {
    // Prüfe ob Cookie-Einstellungen bereits gespeichert wurden
    const saved = localStorage.getItem('cookiePreferences');
    if (saved) {
        const overlay = document.getElementById('cookie-dialog-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
        return;
    }
    
    // Prüfe ob Alpine.js verfügbar ist
    if (typeof Alpine === 'undefined') {
        // Fallback: Einfache Cookie-Dialog-Implementierung ohne Alpine.js
        setTimeout(() => {
            const overlay = document.getElementById('cookie-dialog-overlay');
            if (overlay) {
                overlay.style.display = 'block';
            }
        }, 1000);
    }
});

function cookieBanner() {
    return {
        showDialog: false,
        cookies: {
            necessary: true, // Immer aktiv
            functional: false,
            analytical: false
        },
        
        init: function() {
            // Prüfe ob Cookie-Einstellungen bereits gespeichert wurden
            const saved = localStorage.getItem('cookiePreferences');
            if (saved) {
                const preferences = JSON.parse(saved);
                this.cookies = { ...this.cookies, ...preferences };
                // Banner nicht anzeigen, wenn bereits Einstellungen gespeichert wurden
                const overlay = document.getElementById('cookie-dialog-overlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
                return;
            }
            
            // Banner nach kurzer Verzögerung anzeigen
            setTimeout(() => {
                const overlay = document.getElementById('cookie-dialog-overlay');
                if (overlay) {
                    overlay.style.display = 'block';
                    this.showDialog = true;
                }
            }, 1000);
        },
        
        acceptAll() {
            this.cookies.functional = true;
            this.cookies.analytical = true;
            this.savePreferences();
            this.showSuccessMessage('Alle Cookies wurden akzeptiert.');
            this.hideDialog();
        },
        
        rejectAll() {
            this.cookies.functional = false;
            this.cookies.analytical = false;
            this.savePreferences();
            this.hideDialog();
        },
        
        showSuccessMessage(message) {
            // Erstelle eine Toast-Nachricht
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 z-[10000] bg-green-50 border border-green-200 text-green-900 px-4 py-3 rounded-lg shadow-lg text-sm font-medium';
            toast.textContent = message;
            document.body.appendChild(toast);
            
            // Entferne die Nachricht nach 3 Sekunden
            setTimeout(() => {
                toast.style.transition = 'opacity 0.3s ease-out';
                toast.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        },
        
        savePreferences() {
            localStorage.setItem('cookiePreferences', JSON.stringify(this.cookies));
            localStorage.setItem('cookiePreferencesDate', new Date().toISOString());
            
            // Event für andere Scripts, die auf Cookie-Einstellungen reagieren müssen
            window.dispatchEvent(new CustomEvent('cookiePreferencesUpdated', {
                detail: this.cookies
            }));
        },
        
        hideDialog() {
            this.showDialog = false;
            setTimeout(() => {
                const overlay = document.getElementById('cookie-dialog-overlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
            }, 300);
        }
    }
}
// Global verfügbar für Alpine/Livewire (z. B. bei dynamisch geladenem DOM)
window.cookieBanner = cookieBanner;
</script>

