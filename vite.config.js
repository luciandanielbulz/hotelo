import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/recaptcha.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1', // IPv4 verwenden statt IPv6
        port: 5173,
    },
    optimizeDeps: {
        include: ['alpinejs', 'andere-commonjs-bibliothek'] // Füge hier problematische Module hinzu
    }
});
