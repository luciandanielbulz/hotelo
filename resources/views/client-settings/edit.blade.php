<x-layout>
    <x-slot:heading>
        Statische Client-Einstellungen
    </x-slot:heading>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        <!-- Hauptformular -->
        <form method="POST" action="{{ route('client-settings.update') }}">
            @csrf
            @method('PUT')

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <!-- Warnung -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Wichtiger Hinweis</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Diese Einstellungen sind <strong>NICHT versioniert</strong> und betreffen ALLE Versionen dieses Clients!</p>
                                    <p class="mt-1">Änderungen werden sofort wirksam und sind <strong>NICHT rückgängig</strong> machbar!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nummerierung -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Nummerierung</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <x-input name="lastinvoice" type="number" placeholder="Letzte Rechnungsnummer" label="Letzte Rechnungsnummer" value="{{ old('lastinvoice', $clientSettings->lastinvoice) }}" />
                                <p class="mt-1 text-sm text-orange-600"><strong>Achtung:</strong> Nächste Rechnungsnummer wird {{ $clientSettings->lastinvoice + 1 }}</p>
                            </div>

                            <div>
                                <x-input name="invoicemultiplikator" type="number" placeholder="Rechnung Multiplikator" label="Rechnung Multiplikator" value="{{ old('invoicemultiplikator', $clientSettings->invoicemultiplikator) }}" />
                                <p class="mt-1 text-sm text-gray-600">Faktor für Rechnungsnummern-Berechnung</p>
                            </div>

                            <div>
                                <x-input name="lastoffer" type="number" placeholder="Letzte Angebotsnummer" label="Letzte Angebotsnummer" value="{{ old('lastoffer', $clientSettings->lastoffer) }}" />
                                <p class="mt-1 text-sm text-orange-600"><strong>Achtung:</strong> Nächste Angebotsnummer wird {{ $clientSettings->lastoffer + 1 }}</p>
                            </div>

                            <div>
                                <x-input name="offermultiplikator" type="number" placeholder="Angebot Multiplikator" label="Angebot Multiplikator" value="{{ old('offermultiplikator', $clientSettings->offermultiplikator) }}" />
                                <p class="mt-1 text-sm text-gray-600">Faktor für Angebotsnummern-Berechnung</p>
                            </div>
                        </div>
                    </div>

                    <!-- Formatierung -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Nummernformate</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="invoice_number_format" class="block text-sm font-medium text-gray-700 mb-1">Rechnungsnummer-Format</label>
                                <select name="invoice_number_format" id="invoice_number_format" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('invoice_number_format') border-red-500 @enderror">
                                    <option value="YYYY*1000+N" {{ old('invoice_number_format', $clientSettings->invoice_number_format) == 'YYYY*1000+N' ? 'selected' : '' }}>Jahr*1000+Nummer (z.B. 2025001)</option>
                                    <option value="YYYYNN" {{ old('invoice_number_format', $clientSettings->invoice_number_format) == 'YYYYNN' ? 'selected' : '' }}>Jahr + Nummer (z.B. 20250001)</option>
                                    <option value="YY*1000+N" {{ old('invoice_number_format', $clientSettings->invoice_number_format) == 'YY*1000+N' ? 'selected' : '' }}>Jahr(2-stellig)*1000+Nummer (z.B. 25001)</option>
                                    <option value="YYYY_MM+N" {{ old('invoice_number_format', $clientSettings->invoice_number_format) == 'YYYY_MM+N' ? 'selected' : '' }}>Jahr_Monat+Nummer (z.B. 2025_01001)</option>
                                    <option value="N" {{ old('invoice_number_format', $clientSettings->invoice_number_format) == 'N' ? 'selected' : '' }}>Nur fortlaufende Nummer (z.B. 1, 2, 3...)</option>
                                </select>
                                @error('invoice_number_format')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-600">Format für automatische Rechnungsnummerierung</p>
                            </div>

                            <div>
                                <x-input name="offer_number_format" type="text" placeholder="Wie Rechnungsformat" label="Angebots-Nummer-Format (Optional)" value="{{ old('offer_number_format', $clientSettings->offer_number_format) }}" />
                                <p class="mt-1 text-sm text-gray-600">Leer = Verwendet Rechnungsformat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Präfixe -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Präfixe</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <x-input name="invoice_prefix" type="text" placeholder="R-" label="Rechnungs-Präfix" value="{{ old('invoice_prefix', $clientSettings->invoice_prefix) }}" />
                                <p class="mt-1 text-sm text-gray-600">Präfix für Rechnungsnummern (z.B. "R-", "RECH-")</p>
                            </div>

                            <div>
                                <x-input name="offer_prefix" type="text" placeholder="A-" label="Angebots-Präfix" value="{{ old('offer_prefix', $clientSettings->offer_prefix) }}" />
                                <p class="mt-1 text-sm text-gray-600">Präfix für Angebotsnummern (z.B. "A-", "ANG-")</p>
                            </div>
                        </div>
                    </div>

                    <!-- System-Einstellungen -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">System-Einstellungen</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <x-input name="max_upload_size" type="number" placeholder="2048" label="Maximale Upload-Größe (MB)" value="{{ old('max_upload_size', $clientSettings->max_upload_size) }}" />
                                <p class="mt-1 text-sm text-gray-600">Maximale Dateigröße für Dokument-Uploads</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nummern-Vorschau -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Vorschau der nächsten Nummern</h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nächste Rechnungsnummer:</label>
                                    <div class="mt-1">
                                        <span id="next_invoice_preview" class="text-lg font-mono text-indigo-600">{{ $clientSettings->generateInvoiceNumber() }}</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nächste Angebotsnummer:</label>
                                    <div class="mt-1">
                                        <span id="next_offer_preview" class="text-lg font-mono text-indigo-600">{{ $clientSettings->generateOfferNumber() }}</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="update_preview" class="mt-3 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Vorschau aktualisieren
                            </button>
                        </div>
                    </div>

                    <!-- Aktionen -->
                    <div class="flex items-center justify-end gap-x-6 pt-6">
                        <a href="{{ route('clients.my-settings') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                            Einstellungen speichern
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const updatePreviewBtn = document.getElementById('update_preview');
                const invoicePreview = document.getElementById('next_invoice_preview');
                const offerPreview = document.getElementById('next_offer_preview');

                updatePreviewBtn.addEventListener('click', function() {
                    // Hole aktuelle Formularwerte
                    const formData = new FormData();
                    formData.append('lastinvoice', document.querySelector('[name="lastinvoice"]').value);
                    formData.append('lastoffer', document.querySelector('[name="lastoffer"]').value);
                    formData.append('invoicemultiplikator', document.querySelector('[name="invoicemultiplikator"]').value);
                    formData.append('offermultiplikator', document.querySelector('[name="offermultiplikator"]').value);
                    formData.append('invoice_number_format', document.querySelector('[name="invoice_number_format"]').value);
                    formData.append('offer_number_format', document.querySelector('[name="offer_number_format"]').value);

                    // Anfrage an Preview-Endpoint
                    fetch('{{ route("client-settings.preview") }}', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.next_invoice_number && data.next_offer_number) {
                            invoicePreview.textContent = data.next_invoice_number;
                            offerPreview.textContent = data.next_offer_number;
                        }
                    })
                    .catch(error => {
                        console.error('Fehler beim Aktualisieren der Vorschau:', error);
                    });
                });
            });
        </script>
    @endpush
</x-layout> 