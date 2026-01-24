/**
 * Order Status Change Handler
 * Ersetzt den Inline onchange Handler für CSP-Konformität
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status-select');
        const statusForm = document.getElementById('status-form');

        if (statusSelect && statusForm) {
            statusSelect.addEventListener('change', function() {
                statusForm.submit();
            });
        }

        // Client erstellen Formular mit Bestätigung
        const createClientForm = document.getElementById('create-client-form');
        if (createClientForm) {
            createClientForm.addEventListener('submit', function(e) {
                if (!confirm('Möchten Sie wirklich einen Client aus dieser Bestellung erstellen?')) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
})();
