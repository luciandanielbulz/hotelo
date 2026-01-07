<x-layout>
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">ZIP-Dateinamen Einstellungen</h1>
                    <p class="text-gray-600 mt-2">Erstellen Sie Ihr Dateinamen-Template durch Drag & Drop oder Klicken</p>
                </div>
                <a href="{{ route('invoiceupload.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/70 backdrop-blur-sm text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                    </svg>
                    Zurück
                </a>
            </div>
        </div>

        <!-- Formular -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <form action="{{ route('invoiceupload.update-settings') }}" method="POST" id="templateForm">
                @csrf
                @method('PUT')

                <!-- Drag & Drop Bereich für Template -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dateinamen-Template
                    </label>
                    <div 
                        id="template-builder"
                        class="min-h-[80px] border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50 flex flex-wrap items-center gap-2"
                        ondrop="drop(event)" 
                        ondragover="allowDrop(event)">
                        <input 
                            type="hidden" 
                            id="zip_filename_template" 
                            name="zip_filename_template" 
                            value="{{ old('zip_filename_template', $clientSettings->zip_filename_template ?? '{date}_{index}_{vendor}') }}">
                        <div id="template-preview" class="flex flex-wrap items-center gap-2 min-h-[40px] w-full">
                            <!-- Template-Tokens werden hier dynamisch eingefügt -->
                        </div>
                        <div class="text-gray-400 text-sm italic w-full text-center" id="empty-message">
                            Ziehen Sie Platzhalter hierher oder klicken Sie auf die Buttons unten
                        </div>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <p class="text-sm text-gray-500">
                            Die Dateiendung wird automatisch hinzugefügt
                        </p>
                        <button 
                            type="button" 
                            onclick="clearTemplate()" 
                            class="text-sm text-red-600 hover:text-red-800">
                            Zurücksetzen
                        </button>
                    </div>
                </div>

                <!-- Live-Vorschau -->
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Vorschau:</h3>
                    <div class="flex items-center space-x-2">
                        <code class="bg-white px-3 py-2 rounded text-gray-800 font-mono text-sm flex-1" id="preview-result">
                            {{ old('zip_filename_template', $clientSettings->zip_filename_template ?? '{date}_{index}_{vendor}') }}.pdf
                        </code>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">
                        Beispiel: <span class="font-mono">2025-01-15_1_ABC123.pdf</span>
                    </p>
                </div>

                <!-- Verfügbare Platzhalter mit Drag & Drop -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Verfügbare Platzhalter:</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                        <div 
                            class="placeholders-item bg-white px-3 py-2 rounded-lg border border-blue-300 cursor-move hover:bg-blue-100 hover:shadow-md transition-all text-center"
                            draggable="true" 
                            ondragstart="drag(event)"
                            data-placeholder="{date}"
                            onclick="addPlaceholder('{date}')">
                            <span class="text-blue-600 font-semibold text-sm">Datum</span>
                            <p class="text-xs text-gray-500 mt-1">YYYY-MM-DD</p>
                        </div>
                        <div 
                            class="placeholders-item bg-white px-3 py-2 rounded-lg border border-blue-300 cursor-move hover:bg-blue-100 hover:shadow-md transition-all text-center"
                            draggable="true" 
                            ondragstart="drag(event)"
                            data-placeholder="{index}"
                            onclick="addPlaceholder('{index}')">
                            <span class="text-blue-600 font-semibold text-sm">Nummer</span>
                            <p class="text-xs text-gray-500 mt-1">Laufende Nr.</p>
                        </div>
                        <div 
                            class="placeholders-item bg-white px-3 py-2 rounded-lg border border-blue-300 cursor-move hover:bg-blue-100 hover:shadow-md transition-all text-center"
                            draggable="true" 
                            ondragstart="drag(event)"
                            data-placeholder="{vendor}"
                            onclick="addPlaceholder('{vendor}')">
                            <span class="text-blue-600 font-semibold text-sm">Lieferant</span>
                            <p class="text-xs text-gray-500 mt-1">Verkäufer</p>
                        </div>
                        <div 
                            class="placeholders-item bg-white px-3 py-2 rounded-lg border border-blue-300 cursor-move hover:bg-blue-100 hover:shadow-md transition-all text-center"
                            draggable="true" 
                            ondragstart="drag(event)"
                            data-placeholder="{invoice_number}"
                            onclick="addPlaceholder('{invoice_number}')">
                            <span class="text-blue-600 font-semibold text-sm">Rechnungsnr.</span>
                            <p class="text-xs text-gray-500 mt-1">Rechnungsnummer</p>
                        </div>
                        <div 
                            class="placeholders-item bg-white px-3 py-2 rounded-lg border border-blue-300 cursor-move hover:bg-blue-100 hover:shadow-md transition-all text-center"
                            draggable="true" 
                            ondragstart="drag(event)"
                            data-placeholder="{category}"
                            onclick="addPlaceholder('{category}')">
                            <span class="text-blue-600 font-semibold text-sm">Kategorie</span>
                            <p class="text-xs text-gray-500 mt-1">Kategorie</p>
                        </div>
                        <div 
                            class="placeholders-item bg-white px-3 py-2 rounded-lg border border-blue-300 cursor-move hover:bg-blue-100 hover:shadow-md transition-all text-center"
                            draggable="true" 
                            ondragstart="drag(event)"
                            data-placeholder="{payment_type}"
                            onclick="addPlaceholder('{payment_type}')">
                            <span class="text-blue-600 font-semibold text-sm">Zahlungsart</span>
                            <p class="text-xs text-gray-500 mt-1">Zahlungsart</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mt-3">
                        <strong>Tipp:</strong> Ziehen Sie die Platzhalter in den Template-Bereich oder klicken Sie darauf, um sie hinzuzufügen.
                    </p>
                </div>

                <!-- Trennzeichen -->
                <div class="mb-6 bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Trennzeichen:</h3>
                    <div class="flex gap-2">
                        <button 
                            type="button"
                            onclick="addSeparator('_')"
                            class="bg-white px-4 py-2 rounded-lg border border-purple-300 hover:bg-purple-100 hover:shadow-md transition-all text-sm font-mono">
                            _ (Unterstrich)
                        </button>
                        <button 
                            type="button"
                            onclick="addSeparator('-')"
                            class="bg-white px-4 py-2 rounded-lg border border-purple-300 hover:bg-purple-100 hover:shadow-md transition-all text-sm font-mono">
                            - (Bindestrich)
                        </button>
                        <button 
                            type="button"
                            onclick="addSeparator(' ')"
                            class="bg-white px-4 py-2 rounded-lg border border-purple-300 hover:bg-purple-100 hover:shadow-md transition-all text-sm font-mono">
                            (Leerzeichen)
                        </button>
                    </div>
                </div>

                <!-- Vorgefertigte Templates -->
                <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Vorgefertigte Templates:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <button 
                            type="button"
                            onclick="loadTemplate('{date}_{index}_{vendor}')"
                            class="bg-white px-4 py-3 rounded-lg border border-gray-300 hover:bg-gray-100 hover:shadow-md transition-all text-left">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="bg-blue-900 text-white px-2 py-1 rounded text-xs">Datum</span>
                                <span class="text-gray-400">_</span>
                                <span class="bg-blue-900 text-white px-2 py-1 rounded text-xs">Nummer</span>
                                <span class="text-gray-400">_</span>
                                <span class="bg-blue-900 text-white px-2 py-1 rounded text-xs">Lieferant</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">Standard</p>
                        </button>
                        <button 
                            type="button"
                            onclick="loadTemplate('{date}_{vendor}_{invoice_number}')"
                            class="bg-white px-4 py-3 rounded-lg border border-gray-300 hover:bg-gray-100 hover:shadow-md transition-all text-left">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="bg-blue-900 text-white px-2 py-1 rounded text-xs">Datum</span>
                                <span class="text-gray-400">_</span>
                                <span class="bg-blue-900 text-white px-2 py-1 rounded text-xs">Lieferant</span>
                                <span class="text-gray-400">_</span>
                                <span class="bg-blue-900 text-white px-2 py-1 rounded text-xs">Rechnungsnr.</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">Mit Rechnungsnr.</p>
                        </button>
                        <button 
                            type="button"
                            onclick="loadTemplate('{category}_{date}_{index}')"
                            class="bg-white px-4 py-3 rounded-lg border border-gray-300 hover:bg-gray-100 hover:shadow-md transition-all text-left">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="bg-blue-900 text-white px-2 py-1 rounded text-xs">Kategorie</span>
                                <span class="text-gray-400">_</span>
                                <span class="bg-blue-900 text-white px-2 py-1 rounded text-xs">Datum</span>
                                <span class="text-gray-400">_</span>
                                <span class="bg-blue-900 text-white px-2 py-1 rounded text-xs">Nummer</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">Mit Kategorie</p>
                        </button>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('invoiceupload.index') }}" 
                       class="px-6 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-all duration-300">
                        Abbrechen
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 text-white font-medium rounded-lg hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300">
                        Speichern
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let templateTokens = [];

        // Initialisiere Template beim Laden
        document.addEventListener('DOMContentLoaded', function() {
            const template = document.getElementById('zip_filename_template').value || '{date}_{index}_{vendor}';
            parseTemplate(template);
            renderTemplate();
            updatePreview();
        });

        // Drag & Drop Funktionen
        function allowDrop(ev) {
            ev.preventDefault();
            ev.currentTarget.classList.add('border-blue-500', 'bg-blue-50');
        }

        function drag(ev) {
            ev.dataTransfer.setData("text", ev.target.dataset.placeholder || ev.target.closest('[data-placeholder]').dataset.placeholder);
        }

        function drop(ev) {
            ev.preventDefault();
            ev.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
            const placeholder = ev.dataTransfer.getData("text");
            addPlaceholder(placeholder);
        }

        // Platzhalter hinzufügen
        function addPlaceholder(placeholder) {
            templateTokens.push({ type: 'placeholder', value: placeholder });
            renderTemplate();
            updatePreview();
        }

        // Trennzeichen hinzufügen
        function addSeparator(separator) {
            templateTokens.push({ type: 'separator', value: separator });
            renderTemplate();
            updatePreview();
        }

        // Template parsen
        function parseTemplate(template) {
            templateTokens = [];
            if (!template) {
                return;
            }
            
            // Teile das Template in Platzhalter und Trennzeichen
            const regex = /(\{[^}]+\})|([^\{]+)/g;
            const matches = template.matchAll(regex);
            
            for (const match of matches) {
                const part = match[0];
                if (!part) continue;
                
                if (part.startsWith('{') && part.endsWith('}')) {
                    // Platzhalter
                    templateTokens.push({ type: 'placeholder', value: part });
                } else {
                    // Trennzeichen oder andere Zeichen
                    for (let i = 0; i < part.length; i++) {
                        const char = part[i];
                        if (['_', '-', ' '].includes(char)) {
                            templateTokens.push({ type: 'separator', value: char });
                        }
                    }
                }
            }
            
            // Entferne doppelte Trennzeichen am Anfang/Ende und zwischen Platzhaltern
            const cleaned = [];
            for (let i = 0; i < templateTokens.length; i++) {
                const token = templateTokens[i];
                const prev = cleaned[cleaned.length - 1];
                
                // Überspringe Trennzeichen am Anfang
                if (i === 0 && token.type === 'separator') {
                    continue;
                }
                
                // Überspringe doppelte Trennzeichen
                if (token.type === 'separator' && prev && prev.type === 'separator') {
                    continue;
                }
                
                cleaned.push(token);
            }
            
            // Entferne Trennzeichen am Ende
            while (cleaned.length > 0 && cleaned[cleaned.length - 1].type === 'separator') {
                cleaned.pop();
            }
            
            templateTokens = cleaned;
        }

        // Platzhalter zu deutschen Namen
        function getPlaceholderLabel(placeholder) {
            const labels = {
                '{date}': 'Datum',
                '{index}': 'Nummer',
                '{vendor}': 'Lieferant',
                '{invoice_number}': 'Rechnungsnr.',
                '{category}': 'Kategorie',
                '{payment_type}': 'Zahlungsart'
            };
            return labels[placeholder] || placeholder;
        }

        // Template rendern
        function renderTemplate() {
            const container = document.getElementById('template-preview');
            const emptyMessage = document.getElementById('empty-message');
            
            container.innerHTML = '';
            
            if (templateTokens.length === 0) {
                emptyMessage.style.display = 'block';
                return;
            }
            
            emptyMessage.style.display = 'none';
            
            templateTokens.forEach((token, index) => {
                const element = document.createElement('div');
                element.className = 'inline-flex items-center';
                
                if (token.type === 'placeholder') {
                    const label = getPlaceholderLabel(token.value);
                    element.innerHTML = `
                        <span class="bg-blue-900 text-white px-3 py-1 rounded-lg text-sm flex items-center gap-2">
                            ${label}
                            <button 
                                type="button" 
                                onclick="removeToken(${index})" 
                                class="text-white hover:text-red-200 ml-1">
                                ×
                            </button>
                        </span>
                    `;
                } else {
                    element.innerHTML = `
                        <span class="bg-gray-300 text-gray-700 px-2 py-1 rounded font-mono text-sm flex items-center gap-1">
                            ${token.value === ' ' ? '␣' : token.value}
                            <button 
                                type="button" 
                                onclick="removeToken(${index})" 
                                class="text-gray-600 hover:text-red-600 ml-1 font-bold">
                                ×
                            </button>
                        </span>
                    `;
                }
                
                container.appendChild(element);
            });
            
            updateHiddenInput();
        }

        // Token entfernen
        function removeToken(index) {
            templateTokens.splice(index, 1);
            renderTemplate();
            updatePreview();
        }

        // Hidden Input aktualisieren
        function updateHiddenInput() {
            const template = templateTokens.map(token => token.value).join('');
            document.getElementById('zip_filename_template').value = template;
        }

        // Template zurücksetzen
        function clearTemplate() {
            if (confirm('Möchten Sie das Template wirklich zurücksetzen?')) {
                templateTokens = [];
                renderTemplate();
                updatePreview();
            }
        }

        // Vorgefertigtes Template laden
        function loadTemplate(template) {
            if (confirm('Möchten Sie das aktuelle Template ersetzen?')) {
                parseTemplate(template);
                renderTemplate();
                updatePreview();
            }
        }

        // Vorschau aktualisieren
        function updatePreview() {
            const template = templateTokens.map(token => token.value).join('') || '{date}_{index}_{vendor}';
            
            // Beispielwerte für Vorschau
            const preview = template
                .replace(/{date}/g, '2025-01-15')
                .replace(/{index}/g, '1')
                .replace(/{vendor}/g, 'ABC123')
                .replace(/{invoice_number}/g, 'RE-2025-001')
                .replace(/{category}/g, 'Büro')
                .replace(/{payment_type}/g, 'elektronisch')
                .replace(/_{2,}/g, '_')
                .replace(/^_+|_+$/g, '');
            
            document.getElementById('preview-result').textContent = preview + '.pdf';
        }

        // Drag & Drop visuelles Feedback
        document.getElementById('template-builder').addEventListener('dragleave', function(e) {
            this.classList.remove('border-blue-500', 'bg-blue-50');
        });
    </script>
</x-layout>
