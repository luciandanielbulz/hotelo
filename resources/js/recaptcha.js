/**
 * Google reCAPTCHA v3 Integration
 * Lädt reCAPTCHA und verwaltet Formular-Submits mit Token-Generierung
 */

(function() {
    'use strict';

    // Konfiguration aus dem HTML lesen
    const recaptchaConfig = {
        siteKey: null,
        formSelector: 'form',
        tokenInputId: 'g-recaptcha-response',
        submitButtonId: 'submit-button'
    };

    // Globale Zustandsvariablen
    let recaptchaReady = false;
    let isSubmitting = false;
    let submitHandler = null;

    /**
     * Initialisiert reCAPTCHA
     */
    function initRecaptcha() {
        // Site-Key aus Data-Attribut lesen
        const siteKeyElement = document.querySelector('[data-recaptcha-site-key]');
        if (!siteKeyElement) {
            console.warn('reCAPTCHA Site-Key nicht gefunden');
            return;
        }

        recaptchaConfig.siteKey = siteKeyElement.getAttribute('data-recaptcha-site-key');
        if (!recaptchaConfig.siteKey) {
            console.warn('reCAPTCHA Site-Key ist leer');
            return;
        }

        // Callback-Funktion für reCAPTCHA
        window.onRecaptchaLoad = function() {
            window.recaptchaLoaded = true;
            console.log('reCAPTCHA Script erfolgreich geladen');
        };

        // Lade reCAPTCHA Script mit Callback
        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${recaptchaConfig.siteKey}&onload=onRecaptchaLoad`;
        script.async = true;
        script.defer = true;
        script.onerror = function() {
            console.error('Fehler beim Laden des reCAPTCHA Scripts');
            window.recaptchaLoadError = true;
        };
        document.head.appendChild(script);
    }

    /**
     * Wartet bis reCAPTCHA geladen ist
     */
    function waitForRecaptcha(callback, maxAttempts = 100) {
        let attempts = 0;
        
        function check() {
            attempts++;
            if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.ready === 'function') {
                recaptchaReady = true;
                callback();
            } else if (window.recaptchaLoadError) {
                // Script-Loading ist fehlgeschlagen
                console.warn('reCAPTCHA Script konnte nicht geladen werden - sende Formular ohne Token');
                if (!isSubmitting && submitHandler) {
                    isSubmitting = true;
                    const form = document.querySelector(recaptchaConfig.formSelector);
                    if (form) {
                        form.removeEventListener('submit', submitHandler);
                        form.submit();
                    }
                }
            } else if (attempts < maxAttempts) {
                setTimeout(check, 100);
            } else {
                console.warn(`reCAPTCHA konnte nicht geladen werden nach ${maxAttempts * 100}ms - sende Formular ohne Token`);
                // Fallback: Formular ohne Token absenden
                if (!isSubmitting && submitHandler) {
                    isSubmitting = true;
                    const form = document.querySelector(recaptchaConfig.formSelector);
                    if (form) {
                        form.removeEventListener('submit', submitHandler);
                        form.submit();
                    }
                }
            }
        }
        
        check();
    }

    /**
     * Generiert reCAPTCHA Token und sendet Formular
     */
    function executeRecaptchaAndSubmit(form) {
        if (!recaptchaConfig.siteKey) {
            console.warn('reCAPTCHA Site-Key nicht verfügbar');
            form.submit();
            return;
        }

        if (recaptchaReady && typeof grecaptcha !== 'undefined') {
            grecaptcha.ready(function() {
                grecaptcha.execute(recaptchaConfig.siteKey, {action: 'submit'}).then(function(token) {
                    const tokenInput = document.getElementById(recaptchaConfig.tokenInputId);
                    if (tokenInput) {
                        tokenInput.value = token;
                    }
                    // Entferne Event-Listener und sende Formular
                    form.removeEventListener('submit', submitHandler);
                    form.submit();
                }).catch(function(error) {
                    console.error('reCAPTCHA Fehler:', error);
                    // Entferne Event-Listener und sende Formular ohne Token
                    form.removeEventListener('submit', submitHandler);
                    form.submit();
                });
            });
        } else {
            // Warte auf reCAPTCHA
            waitForRecaptcha(function() {
                grecaptcha.ready(function() {
                    grecaptcha.execute(recaptchaConfig.siteKey, {action: 'submit'}).then(function(token) {
                        const tokenInput = document.getElementById(recaptchaConfig.tokenInputId);
                        if (tokenInput) {
                            tokenInput.value = token;
                        }
                        // Entferne Event-Listener und sende Formular
                        form.removeEventListener('submit', submitHandler);
                        form.submit();
                    }).catch(function(error) {
                        console.error('reCAPTCHA Fehler:', error);
                        // Entferne Event-Listener und sende Formular ohne Token
                        form.removeEventListener('submit', submitHandler);
                        form.submit();
                    });
                });
            });
        }
    }

    /**
     * Initialisiert Formular-Submit-Handler
     */
    function initFormHandler() {
        const form = document.querySelector(recaptchaConfig.formSelector);
        if (!form) {
            console.warn('Formular nicht gefunden');
            return;
        }

        const submitButton = document.getElementById(recaptchaConfig.submitButtonId);

        // Submit-Handler
        submitHandler = function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return; // Verhindere doppelte Submits
            }
            
            e.preventDefault();
            isSubmitting = true;
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Wird gesendet...';
            }
            
            executeRecaptchaAndSubmit(form);
        };
        
        form.addEventListener('submit', submitHandler);
    }

    /**
     * Initialisierung wenn DOM bereit ist
     */
    function init() {
        // Prüfe ob reCAPTCHA auf dieser Seite benötigt wird
        const recaptchaElement = document.querySelector('[data-recaptcha-site-key]');
        if (!recaptchaElement) {
            return; // Kein reCAPTCHA auf dieser Seite
        }

        // Initialisiere reCAPTCHA
        initRecaptcha();

        // Initialisiere Formular-Handler wenn DOM bereit ist
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initFormHandler);
        } else {
            initFormHandler();
        }
    }

    // Starte Initialisierung
    init();
})();
