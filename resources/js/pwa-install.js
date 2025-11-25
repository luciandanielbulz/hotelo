// PWA Install Prompt Handler
let deferredPrompt;
let installButton = null;

// Create install button if it doesn't exist
function createInstallButton() {
    // Check if button already exists
    if (document.getElementById('pwa-install-button')) {
        return;
    }

    // Create button element
    const button = document.createElement('button');
    button.id = 'pwa-install-button';
    button.className = 'fixed bottom-4 right-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full shadow-lg z-50 flex items-center gap-2';
    button.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
        </svg>
        App installieren
    `;
    
    button.addEventListener('click', async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response to the install prompt: ${outcome}`);
            deferredPrompt = null;
            button.style.display = 'none';
        }
    });

    document.body.appendChild(button);
    installButton = button;
}

// Listen for the beforeinstallprompt event
window.addEventListener('beforeinstallprompt', (e) => {
    console.log('beforeinstallprompt event fired');
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    // Stash the event so it can be triggered later
    deferredPrompt = e;
    // Show install button
    createInstallButton();
});

// Listen for app installed event
window.addEventListener('appinstalled', () => {
    console.log('PWA was installed');
    deferredPrompt = null;
    if (installButton) {
        installButton.style.display = 'none';
    }
    // Show success message
    if (window.dispatchEvent) {
        window.dispatchEvent(new CustomEvent('notify', {
            detail: {
                message: 'App erfolgreich installiert!',
                type: 'success'
            }
        }));
    }
});

// Check if app is already installed
if (window.matchMedia('(display-mode: standalone)').matches) {
    console.log('App is running in standalone mode');
    // Hide install button if app is already installed
    if (installButton) {
        installButton.style.display = 'none';
    }
}

// Check on page load if app can be installed
window.addEventListener('load', () => {
    // Check if app is already installed
    if (window.matchMedia('(display-mode: standalone)').matches) {
        return;
    }
    
    // Check if browser supports installation
    if ('serviceWorker' in navigator && 'BeforeInstallPromptEvent' in window) {
        // Browser supports PWA installation
        console.log('PWA installation supported');
    }
});

