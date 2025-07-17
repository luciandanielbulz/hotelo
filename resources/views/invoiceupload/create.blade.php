<x-layout>
    <!-- Moderner Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Beleg hochladen</h1>
            <p class="text-gray-600">Laden Sie einen neuen Beleg hoch und vervollständigen Sie die Informationen</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('invoiceupload.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/70 backdrop-blur-sm text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                </svg>
                Zurück zur Übersicht
            </a>
        </div>
    </div>

    <!-- Hauptcontainer -->
    <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
        <form action="{{ route('invoiceupload.upload.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Erfolgsmeldung -->
            @if(session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Fehlermeldungen -->
            @if($errors->any())
                <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800 mb-2">Bitte korrigieren Sie folgende Fehler:</h3>
                            <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Beleg Art Selection -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <label class="text-sm font-bold text-blue-700">Beleg-Art *</label>
                    </div>
                    <div class="flex space-x-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="type" value="expense" checked 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm font-medium text-gray-700 bg-white/60 px-3 py-1 rounded-lg">📤 Ausgabe</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="type" value="income" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm font-medium text-gray-700 bg-white/60 px-3 py-1 rounded-lg">📥 Einnahme</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Hauptformular in zwei Spalten -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Linke Spalte: Grunddaten -->
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-lg p-6 border border-gray-200 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Grunddaten
                    </h3>

                    <!-- Belegnummer -->
                    <div>
                        <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">Belegnummer *</label>
                        <input type="text" name="invoice_number" id="invoice_number" required
                               placeholder="z. B. Rechnungsnummer"
                               class="w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                    </div>

                    <!-- Lieferant -->
                    <div>
                        <label for="invoice_vendor" class="block text-sm font-medium text-gray-700 mb-2">Lieferant *</label>
                        <input type="text" name="invoice_vendor" id="invoice_vendor" required
                               placeholder="Lieferant suchen / erstellen"
                               class="w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                    </div>

                    <!-- Beschreibung -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                        <textarea name="description" id="description" rows="3"
                                  placeholder="Beschreibung des Belegs"
                                  class="w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md resize-none"></textarea>
                    </div>

                    <!-- Kategorie -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategorie</label>
                        <select name="category_id" id="category_id" 
                                class="w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                            <option value="">Kategorie wählen (optional)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        style="color: {{ $category->color }};">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(\Schema::hasColumn('invoice_uploads', 'payment_type'))
                    <!-- Zahlungsart -->
                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-2">Zahlungsart</label>
                        <select name="payment_type" id="payment_type" 
                                class="w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                            <option value="elektronisch" selected>💳 elektronisch</option>
                            <option value="nicht elektronisch">💵 nicht elektronisch</option>
                            <option value="Kreditkarte">💎 Kreditkarte</option>
                        </select>
                    </div>
                    @endif
                </div>

                <!-- Rechte Spalte: Datum & Upload -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-6 border border-purple-200 space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m0 0a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2m8 0V9a2 2 0 00-2-2H8a2 2 0 00-2 2v.01"/>
                        </svg>
                        Datum & Datei
                    </h3>

                    <!-- Belegdatum -->
                    <div>
                        <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Belegdatum *</label>
                        <input type="date" name="invoice_date" id="invoice_date" required
                               class="w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                    </div>

                    <!-- PDF Upload -->
                    <div>
                        <label for="invoice_pdf" class="block text-sm font-medium text-gray-700 mb-2">PDF-Datei *</label>
                        <div class="relative">
                            <input type="file" name="invoice_pdf" id="invoice_pdf" required accept=".pdf"
                                   class="w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">📎 Nur PDF-Dateien werden akzeptiert</p>
                    </div>

                    <!-- Upload-Info Box -->
                    <div class="bg-white/60 rounded-lg p-4 border border-purple-300">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-purple-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-purple-800">Upload-Hinweise</h4>
                                <ul class="text-xs text-purple-700 mt-1 space-y-1">
                                    <li>• Maximale Dateigröße: 10 MB</li>
                                    <li>• Format: PDF</li>
                                    <li>• Klarer, lesbarer Scan empfohlen</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Finanzbereich -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6 border border-green-200 mb-8">
                <div class="flex items-center mb-6">
                    <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Finanzielle Daten</h3>
                </div>

                <!-- Brutto/Netto Toggle -->
                <div class="mb-6 bg-white/60 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Eingabemodus</label>
                    <div class="flex space-x-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="tax_type" value="gross" checked onchange="calculateAmounts()"
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                            <span class="ml-2 text-sm font-medium text-gray-700 bg-green-100 px-4 py-2 rounded-lg">💰 Brutto-Eingabe</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="tax_type" value="net" onchange="calculateAmounts()"
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                            <span class="ml-2 text-sm font-medium text-gray-700 bg-white/60 px-4 py-2 rounded-lg">📊 Netto-Eingabe</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Betrag -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Betrag *</label>
                        <div class="relative">
                            <input type="number" name="amount" id="amount" step="0.01" min="0" required
                                   placeholder="0,00"
                                   class="w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md pr-12"
                                   onchange="calculateAmounts()">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">€</span>
                        </div>
                    </div>

                    <!-- Währung -->
                    <div>
                        <label for="currency_id" class="block text-sm font-medium text-gray-700 mb-2">Währung</label>
                        <select name="currency_id" id="currency_id" onchange="calculateAmounts()"
                                class="w-full px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                            <option value="">Währung wählen</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ $currency->is_default ? 'selected' : '' }}>
                                    {{ $currency->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Umsatzsteuer -->
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Umsatzsteuer *</label>
                        <div class="flex">
                            <input type="number" name="tax_rate" id="tax_rate" value="20" step="0.01" min="0" max="100"
                                   class="flex-1 px-4 py-3 bg-white/60 backdrop-blur-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                   onchange="calculateAmounts()">
                            <span class="inline-flex items-center px-4 py-3 border border-l-0 border-gray-300 bg-gray-100 text-gray-600 text-sm rounded-r-lg font-medium">%</span>
                        </div>
                    </div>
                </div>

                <!-- Berechnete Werte -->
                <div class="mt-6" id="calculated_amounts" style="display: none;">
                    <div class="bg-white/80 backdrop-blur-sm rounded-lg p-6 border border-green-300 shadow-lg">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <h4 class="text-sm font-bold text-gray-900">Automatische Berechnung:</h4>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center bg-blue-50 rounded-lg p-4">
                                <div class="text-xs text-blue-600 font-medium mb-1">NETTOBETRAG</div>
                                <div id="net_amount_display" class="font-bold text-lg text-blue-900">0,00 €</div>
                            </div>
                            <div class="text-center bg-yellow-50 rounded-lg p-4">
                                <div class="text-xs text-yellow-600 font-medium mb-1">STEUERBETRAG</div>
                                <div id="tax_amount_display" class="font-bold text-lg text-yellow-900">0,00 €</div>
                            </div>
                            <div class="text-center bg-green-50 rounded-lg p-4 border-2 border-green-300">
                                <div class="text-xs text-green-600 font-medium mb-1">BRUTTOBETRAG</div>
                                <div id="gross_amount_display" class="font-bold text-xl text-green-900">0,00 €</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('invoiceupload.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-white/70 backdrop-blur-sm text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Abbrechen
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-lg hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Beleg speichern
                </button>
            </div>
        </form>
    </div>

    <script>
        function calculateAmounts() {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
            const taxType = document.querySelector('input[name="tax_type"]:checked').value;
            const currencySelect = document.getElementById('currency_id');
            const selectedCurrency = currencySelect.options[currencySelect.selectedIndex];
            const currencySymbol = selectedCurrency.value ? selectedCurrency.textContent : 'EUR';

            if (amount > 0 && taxRate >= 0) {
                let netAmount, taxAmount, grossAmount;

                if (taxType === 'gross') {
                    // Bruttoeingabe - berechne Netto und Steuer
                    grossAmount = amount;
                    netAmount = amount / (1 + (taxRate / 100));
                    taxAmount = amount - netAmount;
                } else {
                    // Nettoeingabe - berechne Brutto und Steuer
                    netAmount = amount;
                    taxAmount = amount * (taxRate / 100);
                    grossAmount = amount + taxAmount;
                }

                // Anzeige aktualisieren mit deutschem Format
                document.getElementById('net_amount_display').textContent = 
                    netAmount.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ' + currencySymbol;
                document.getElementById('tax_amount_display').textContent = 
                    taxAmount.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ' + currencySymbol;
                document.getElementById('gross_amount_display').textContent = 
                    grossAmount.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ' + currencySymbol;

                document.getElementById('calculated_amounts').style.display = 'block';
            } else {
                document.getElementById('calculated_amounts').style.display = 'none';
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('amount').addEventListener('input', calculateAmounts);
            document.getElementById('tax_rate').addEventListener('input', calculateAmounts);
            document.querySelectorAll('input[name="tax_type"]').forEach(radio => {
                radio.addEventListener('change', calculateAmounts);
            });
            document.getElementById('currency_id').addEventListener('change', calculateAmounts);
        });
    </script>
</x-layout>
