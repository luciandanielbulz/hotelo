<x-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-blue-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Rechnung bearbeiten</h2>
                    <p class="text-blue-100 text-sm mt-1">Bearbeiten Sie die Details der Rechnung (ID: {{ $invoice->id }})</p>
                </div>

                <form method="POST" action="{{ route('invoiceupload.update', $invoice->id) }}" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                    @if ($errors->any())
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
                                            @foreach ($errors->all() as $error)
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
                                <input type="radio" name="type" value="expense" {{ old('type', $invoice->type ?? 'expense') == 'expense' ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm font-medium text-gray-700">Ausgabe</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="income" {{ old('type', $invoice->type ?? 'expense') == 'income' ? 'checked' : '' }}
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
                                <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" required
                                       placeholder="z. B. Rechnungsnummer"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Lieferant -->
                            <div>
                                <label for="invoice_vendor" class="block text-sm font-bold text-gray-700 mb-2">Lieferant *</label>
                                <input type="text" name="invoice_vendor" id="invoice_vendor" value="{{ old('invoice_vendor', $invoice->invoice_vendor) }}" required
                                       placeholder="Lieferant suchen / erstellen"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Beschreibung -->
                            <div>
                                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Beschreibung</label>
                                <textarea name="description" id="description" rows="3"
                                          placeholder="Beschreibung des Belegs"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $invoice->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Rechte Spalte -->
                        <div class="space-y-6">
                            <!-- Belegdatum -->
                            <div>
                                <label for="invoice_date" class="block text-sm font-bold text-gray-700 mb-2">Belegdatum *</label>
                                <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d')) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- PDF Upload -->
                            <div>
                                <label for="invoice_pdf" class="block text-sm font-bold text-gray-700 mb-2">PDF-Datei</label>
                                
                                @if($invoice->filepath)
                                    <div class="mb-3 p-3 bg-blue-50 rounded-md border border-blue-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm text-blue-700 font-medium">
                                                    {{ basename($invoice->filepath) }}
                                                </span>
                                            </div>
                                            <a href="{{ route('invoiceupload.show_invoice', $invoice->id) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-3 py-1 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-white hover:bg-blue-50">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Anzeigen
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                
                                <input type="file" name="invoice_pdf" id="invoice_pdf" accept=".pdf"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">{{ $invoice->filepath ? 'Neue Datei wählen, um die aktuelle zu ersetzen (optional)' : 'PDF-Datei auswählen' }}</p>
                            </div>

                            @if(\Schema::hasColumn('invoice_uploads', 'payment_type'))
                            <!-- Zahlungsart -->
                            <div>
                                <label for="payment_type" class="block text-sm font-bold text-gray-700 mb-2">Zahlungsart</label>
                                <select name="payment_type" id="payment_type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="elektronisch" {{ old('payment_type', $invoice->payment_type ?? 'elektronisch') == 'elektronisch' ? 'selected' : '' }}>elektronisch</option>
                                    <option value="nicht elektronisch" {{ old('payment_type', $invoice->payment_type ?? 'elektronisch') == 'nicht elektronisch' ? 'selected' : '' }}>nicht elektronisch</option>
                                    <option value="Kreditkarte" {{ old('payment_type', $invoice->payment_type ?? 'elektronisch') == 'Kreditkarte' ? 'selected' : '' }}>Kreditkarte</option>
                                </select>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Finanzbereich -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <!-- Brutto/Netto Toggle -->
                        <div class="mb-6">
                            <div class="flex items-center space-x-8">
                                <label class="flex items-center">
                                    <input type="radio" name="tax_type" value="gross" {{ old('tax_type', $invoice->tax_type ?? 'gross') == 'gross' ? 'checked' : '' }} onchange="calculateAmounts()"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700 {{ old('tax_type', $invoice->tax_type ?? 'gross') == 'gross' ? 'bg-blue-100 px-3 py-1 rounded' : '' }}">Brutto</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="tax_type" value="net" {{ old('tax_type', $invoice->tax_type ?? 'gross') == 'net' ? 'checked' : '' }} onchange="calculateAmounts()"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700 {{ old('tax_type', $invoice->tax_type ?? 'gross') == 'net' ? 'bg-blue-100 px-3 py-1 rounded' : '' }}">Netto</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Betrag -->
                            <div>
                                <label for="amount" class="block text-sm font-bold text-gray-700 mb-2">Betrag *</label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount', $invoice->amount) }}" step="0.01" min="0" required
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
                                        <option value="{{ $currency->id }}" {{ old('currency_id', $invoice->currency_id) == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Umsatzsteuer -->
                            <div>
                                <label for="tax_rate" class="block text-sm font-bold text-gray-700 mb-2">Umsatzsteuer in % *</label>
                                <div class="flex">
                                    <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $invoice->tax_rate ?? 19) }}" step="0.01" min="0" max="100"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           onchange="calculateAmounts()">
                                    <span class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-r-md">%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Berechnete Werte -->
                        <div class="mt-6" id="calculated_amounts" style="display: {{ $invoice->amount ? 'block' : 'none' }};">
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <h4 class="text-sm font-bold text-gray-900 mb-3">Berechnete Werte:</h4>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div class="text-center">
                                        <div class="text-gray-600">Nettobetrag</div>
                                        <div id="net_amount_display" class="font-bold text-lg text-gray-900">
                                            {{ $invoice->net_amount ? number_format($invoice->net_amount, 2, ',', '.') . ' ' . ($invoice->currency->symbol ?? '€') : '0,00 €' }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-gray-600">Steuerbetrag</div>
                                        <div id="tax_amount_display" class="font-bold text-lg text-gray-900">
                                            {{ $invoice->tax_amount ? number_format($invoice->tax_amount, 2, ',', '.') . ' ' . ($invoice->currency->symbol ?? '€') : '0,00 €' }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-gray-600">Bruttobetrag</div>
                                        <div id="gross_amount_display" class="font-bold text-lg text-blue-600">
                                            {{ $invoice->amount ? number_format($invoice->amount, 2, ',', '.') . ' ' . ($invoice->currency->symbol ?? '€') : '0,00 €' }}
                                        </div>
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
                            Rechnung aktualisieren
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
