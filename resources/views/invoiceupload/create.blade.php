<x-layout>
    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-xl font-semibold text-black">Rechnung hochladen</h2>
            <p class="text-sm mt-1">Bitte füllen Sie die folgenden Informationen aus, um eine neue Rechnung hochzuladen.</p>
        </div>

        <div class="sm:col-span-1 md:col-span-5">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <form action="{{ route('invoiceupload.upload.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf

                    @if(session('success'))
                        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm text-red-700">
                                        <ul class="list-disc space-y-1 pl-5">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Beleg Art Toggle -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Beleg *</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="type" value="expense" checked 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm font-medium text-gray-700">Ausgabe</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="income" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm font-medium text-gray-700">Einnahme</span>
                            </label>
                        </div>
                    </div>

                    <!-- Hauptformular in zwei Spalten -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Linke Spalte -->
                        <div class="space-y-6">
                            <!-- Belegnummer -->
                            <div>
                                <label for="invoice_number" class="block text-sm font-bold text-gray-700 mb-2">Belegnummer *</label>
                                <input type="text" name="invoice_number" id="invoice_number" required
                                       placeholder="z. B. Rechnungsnummer"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Lieferant -->
                            <div>
                                <label for="invoice_vendor" class="block text-sm font-bold text-gray-700 mb-2">Lieferant *</label>
                                <input type="text" name="invoice_vendor" id="invoice_vendor" required
                                       placeholder="Lieferant suchen / erstellen"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Beschreibung -->
                            <div>
                                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Beschreibung</label>
                                <textarea name="description" id="description" rows="3"
                                          placeholder="Beschreibung des Belegs"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>

                            <!-- Kategorie -->
                            <div>
                                <label for="category_id" class="block text-sm font-bold text-gray-700 mb-2">Kategorie</label>
                                <select name="category_id" id="category_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                                <label for="payment_type" class="block text-sm font-bold text-gray-700 mb-2">Zahlungsart</label>
                                <select name="payment_type" id="payment_type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="elektronisch" selected>elektronisch</option>
                                    <option value="nicht elektronisch">nicht elektronisch</option>
                                    <option value="Kreditkarte">Kreditkarte</option>
                                </select>
                            </div>
                            @endif
                        </div>

                        <!-- Rechte Spalte -->
                        <div class="space-y-6">
                            <!-- Belegdatum -->
                            <div>
                                <label for="invoice_date" class="block text-sm font-bold text-gray-700 mb-2">Belegdatum *</label>
                                <input type="date" name="invoice_date" id="invoice_date" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- PDF Upload -->
                            <div>
                                <label for="invoice_pdf" class="block text-sm font-bold text-gray-700 mb-2">PDF-Datei *</label>
                                <input type="file" name="invoice_pdf" id="invoice_pdf" required accept=".pdf"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                    </div>

                    <!-- Finanzbereich -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <!-- Brutto/Netto Toggle -->
                        <div class="mb-6">
                            <div class="flex items-center space-x-8">
                                <label class="flex items-center">
                                    <input type="radio" name="tax_type" value="gross" checked onchange="calculateAmounts()"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700 bg-blue-100 px-3 py-1 rounded">Brutto</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="tax_type" value="net" onchange="calculateAmounts()"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Netto</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Betrag -->
                            <div>
                                <label for="amount" class="block text-sm font-bold text-gray-700 mb-2">Betrag *</label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0" required
                                       placeholder="0,00"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       onchange="calculateAmounts()">
                            </div>

                            <!-- Währung -->
                            <div>
                                <label for="currency_id" class="block text-sm font-bold text-gray-700 mb-2">Währung</label>
                                <select name="currency_id" id="currency_id" onchange="calculateAmounts()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                                <label for="tax_rate" class="block text-sm font-bold text-gray-700 mb-2">Umsatzsteuer in % *</label>
                                <div class="flex">
                                    <input type="number" name="tax_rate" id="tax_rate" value="20" step="0.01" min="0" max="100"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           onchange="calculateAmounts()">
                                    <span class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-r-md">%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Berechnete Werte -->
                        <div class="mt-6" id="calculated_amounts" style="display: none;">
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <h4 class="text-sm font-bold text-gray-900 mb-3">Berechnete Werte:</h4>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div class="text-center">
                                        <div class="text-gray-600">Nettobetrag</div>
                                        <div id="net_amount_display" class="font-bold text-lg text-gray-900">0,00 €</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-gray-600">Steuerbetrag</div>
                                        <div id="tax_amount_display" class="font-bold text-lg text-gray-900">0,00 €</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-gray-600">Bruttobetrag</div>
                                        <div id="gross_amount_display" class="font-bold text-lg text-blue-600">0,00 €</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('invoiceupload.index') }}"
                           class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Abbrechen
                        </a>
                        <button type="submit"
                                class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Rechnung speichern
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
