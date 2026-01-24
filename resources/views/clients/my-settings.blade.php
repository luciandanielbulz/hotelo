<x-layout>

    <!-- Button zu den statischen Einstellungen -->
    @if(auth()->user()->hasPermission('edit_client_settings'))
        <div class="mb-6 flex justify-end">
            <a href="{{ route('client-settings.edit', ['return_to' => route('clients.my-settings')]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Angebots- und Rechnungsnummern
            </a>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <!-- Linke Spalte: Überschrift -->
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Firmen-Einstellungen</h2>
            <p class="mt-1 text-sm text-gray-600">Bearbeiten Sie hier Ihre Firmen-Einstellungen.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('clients.update-my-settings') }}" method="POST" enctype="multipart/form-data" class="sm:col-span-1 md:col-span-5">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                <!-- Name -->
                <div class="sm:col-span-1">
                    <x-input name="clientname" type="text" placeholder="Name" label="Name" value="{{ old('clientname', $clients->clientname) }}" />
                </div>

                <!-- Firma -->
                <div class="sm:col-span-1">
                    <x-input name="companyname" type="text" placeholder="Firma" label="Firma" value="{{ old('companyname', $clients->companyname) }}" />
                </div>

                <!-- Firmenart -->
                <div class="sm:col-span-1">
                    <x-input name="business" type="text" placeholder="Firmenart" label="Firmenart" value="{{ old('business', $clients->business) }}" />
                </div>

                <!-- UID -->
                <div class="sm:col-span-1">
                    <x-input name="vat_number" type="text" placeholder="UID" label="UID" value="{{ old('vat_number', $clients->vat_number) }}" />
                </div>
            </div>

            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Adresse -->
                <div class="sm:col-span-1">
                    <x-input name="address" type="text" placeholder="Adresse" label="Adresse" value="{{ old('address', $clients->address) }}" />
                </div>

                <!-- Postleitzahl -->
                <div class="sm:col-span-1">
                    <x-input name="postalcode" type="text" placeholder="Postleitzahl" label="Postleitzahl" value="{{ old('postalcode', $clients->postalcode) }}" />
                </div>

                <!-- Ort -->
                <div class="sm:col-span-1">
                    <x-input name="location" type="text" placeholder="Ort" label="Ort" value="{{ old('location', $clients->location) }}" />
                </div>

                <!-- Land -->
                <div class="sm:col-span-1">
                    @php
                        $countryOptions = [];
                        foreach ($countries as $country) {
                            $countryOptions[$country->id] = $country->name_de ?? $country->name;
                        }
                        $selectedCountryId = old('country_id', $clients->country_id);
                        // Stelle sicher, dass country_id als Integer behandelt wird
                        $selectedCountryId = $selectedCountryId ? (int)$selectedCountryId : null;
                    @endphp
                    <x-dropdown_body name="country_id" id="country_id" label="Land" :options="$countryOptions" :selected="$selectedCountryId" placeholder="Bitte auswählen" />
                </div>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Email -->
                <div class="sm:col-span-1">
                    <x-input name="email" type="email" placeholder="Email" label="Email" value="{{ old('email', $clients->email) }}" />
                </div>

                <!-- Telefon -->
                <div class="sm:col-span-1">
                    <x-input name="phone" type="text" placeholder="Telefon" label="Telefon" value="{{ old('phone', $clients->phone) }}" />
                </div>
            </div>

            <!-- Steuerliche Informationen -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Steuerliche Informationen</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <!-- Konditionen -->
                    <div class="sm:col-span-1">
                        <x-dropdown_body name="tax_id" id="tax_id" value="" :options="$taxrates->pluck('taxrate', 'id')" :selected="old('tax_id', $clients->tax_id)" label="Steuerhöhe" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-dropdown_body name="smallbusiness" label="Kleinunternehmer" :options="['1' => 'Ja','0' => 'Nein']" :selected="old('smallbusiness', $clients->smallbusiness)" placeholder="Bitte wählen..." />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="tax_number" type="text" placeholder="Steuernummer" label="Steuernummer" value="{{ old('tax_number', $clients->tax_number) }}" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="company_registration_number" type="text" placeholder="Firmenbuchnummer" label="Firmenbuchnummer" value="{{ old('company_registration_number', $clients->company_registration_number) }}" />
                    </div>
                </div>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-1">
                        <x-input name="management" type="text" placeholder="Geschäftsführung" label="Geschäftsführung" value="{{ old('management', $clients->management) }}" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="regional_court" type="text" placeholder="Handelsregistergericht" label="Handelsregistergericht" value="{{ old('regional_court', $clients->regional_court) }}" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="dgnr" type="text" placeholder="DGNR" label="DGNR" value="{{ old('dgnr', $clients->dgnr) }}" />
                    </div>
                </div>
            </div>

            <!-- Webseite -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Webseite</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-1">
                        <x-input name="webpage" type="text" placeholder="Web-Seite" label="Web-Seite" value="{{ old('webpage', $clients->webpage) }}" />
                    </div>
                </div>
            </div>

            <!-- Bankverbindung -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Bankverbindung</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-1">
                        <x-input name="bank" type="text" placeholder="Bankname" label="Bankname" value="{{ old('bank', $clients->bank) }}" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="accountnumber" type="text" placeholder="Kontonummer" label="Kontonummer" value="{{ old('accountnumber', $clients->accountnumber) }}" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="bic" type="text" placeholder="BIC" label="BIC" value="{{ old('bic', $clients->bic) }}" />
                    </div>
                </div>
            </div>

            <!-- Versteckte Felder -->
            <input type="hidden" name="style" value="{{ old('style', $clients->style) }}">

            <!-- E-Mail Signatur -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">E-Mail Signatur</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-6">
                        <label for="signature" class="block text-sm/6 font-medium text-gray-900 mb-1">E-Mail Signatur</label>
                        <div class="mt-1">
                            <textarea name="signature" id="signature" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('signature') border-red-500 outline-red-500 @enderror">{{ old('signature', $clients->signature) }}</textarea>
                            @error('signature')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokument-Fußzeile (Angebote & Rechnungen) -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Dokument-Fußzeile (Angebote & Rechnungen)</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-6">
                        <label for="document_footer" class="block text-sm/6 font-medium text-gray-900 mb-1">Dokument-Fußzeile</label>
                        <div class="mt-1">
                            <textarea name="document_footer" id="document_footer" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('document_footer') border-red-500 outline-red-500 @enderror">{{ old('document_footer', $clients->document_footer) }}</textarea>
                            @error('document_footer')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 rounded-md border border-gray-200 bg-gray-50 p-3">
                                <p class="text-sm text-gray-700 font-medium">Verfügbare Platzhalter</p>
                                <ul class="mt-1 text-sm text-gray-600 list-disc list-inside">
                                    <li><code>{creator}</code> – Vollständiger Name des Erstellers (Rechnung/Angebot); fällt auf den aktuell eingeloggten Benutzer zurück.</li>
                                </ul>
                                <p class="mt-1 text-sm text-gray-500">Beispiel: Mit freundlichen Grüßen<br><code>{creator}</code></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Logo</h3>
                <div class="grid md:grid-cols-3 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-2">
                        <label for="logo" class="block text-sm/6 font-medium text-gray-900 mb-1">Logo</label>
                        <div class="mt-1 flex items-center gap-x-3">
                            <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/jpg,image/gif" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none @error('logo') border-red-500 @enderror" onchange="previewImage(this)">
                        </div>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-1">
                        @php
                            $logoExists = $clients->logo && \Storage::disk('public')->exists('logos/' . $clients->logo);
                            $logoUrl = $clients->logo && $logoExists ? url('/storage/logos/' . rawurlencode($clients->logo)) : null;
                        @endphp
                        @if($logoExists && $logoUrl)
                            <div class="mt-1">
                                <p class="text-sm font-medium text-gray-900 mb-2">Aktuelles Logo:</p>
                                <img src="{{ $logoUrl }}" alt="Aktuelles Logo" class="h-20 w-auto object-contain border rounded" onerror="this.style.display='none';">
                            </div>
                        @endif
                        <div id="logo-preview" class="hidden mt-2">
                            <p class="text-sm font-medium text-gray-900 mb-2">Neues Logo:</p>
                            <img id="preview" src="#" alt="Logo Vorschau" class="h-20 w-auto object-contain border rounded">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Farbe -->
            <div class="pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Farbe</h3>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input name="color" type="color" placeholder="Farbe für Rechnungen und Angebote" label="Farbe für Rechnungen und Angebote" value="{{ old('color', $clients->color) }}" />
                    </div>
                </div>
            </div>

            <!-- Nummernformate -->
            <div class="border-t border-gray-200 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Nummernformate</h3>
                <div class="grid md:grid-cols-2 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-1">
                        <label for="invoice_number_format" class="block text-sm font-medium text-gray-700 mb-1">Rechnungsnummer-Format</label>
                        <select name="invoice_number_format" id="invoice_number_format" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-700 sm:text-sm @error('invoice_number_format') border-red-500 @enderror">
                            <option value="YYYY*1000+N" {{ old('invoice_number_format', $clients->invoice_number_format ?? ($clientSettings->invoice_number_format ?? '')) == 'YYYY*1000+N' ? 'selected' : '' }}>Jahr*1000+Nummer (z.B. 2025001)</option>
                            <option value="YYYYNN" {{ old('invoice_number_format', $clients->invoice_number_format ?? ($clientSettings->invoice_number_format ?? '')) == 'YYYYNN' ? 'selected' : '' }}>Jahr + Nummer (z.B. 20250001)</option>
                            <option value="YY*1000+N" {{ old('invoice_number_format', $clients->invoice_number_format ?? ($clientSettings->invoice_number_format ?? '')) == 'YY*1000+N' ? 'selected' : '' }}>Jahr(2-stellig)*1000+Nummer (z.B. 25001)</option>
                            <option value="YYYY_MM+N" {{ old('invoice_number_format', $clients->invoice_number_format ?? ($clientSettings->invoice_number_format ?? '')) == 'YYYY_MM+N' ? 'selected' : '' }}>Jahr_Monat+Nummer (z.B. 2025_01001)</option>
                            <option value="YYYY*10000+N+1000" {{ old('invoice_number_format', $clients->invoice_number_format ?? ($clientSettings->invoice_number_format ?? '')) == 'YYYY*10000+N+1000' ? 'selected' : '' }}>Jahr*10000+(Nummer+1000) - Rechnungen (z.B. 20251001)</option>
                            <option value="N" {{ old('invoice_number_format', $clients->invoice_number_format ?? ($clientSettings->invoice_number_format ?? '')) == 'N' ? 'selected' : '' }}>Nur fortlaufende Nummer (z.B. 1, 2, 3...)</option>
                        </select>
                        @error('invoice_number_format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-600">Format für automatische Rechnungsnummerierung</p>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="offer_number_format" class="block text-sm font-medium text-gray-700 mb-1">Angebotsnummer-Format</label>
                        <select name="offer_number_format" id="offer_number_format" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring-blue-700 sm:text-sm @error('offer_number_format') border-red-500 @enderror">
                            <option value="" {{ old('offer_number_format', $clients->offer_number_format ?? ($clientSettings->offer_number_format ?? '')) == '' ? 'selected' : '' }}>Wie Rechnungsformat</option>
                            <option value="YYYY*1000+N" {{ old('offer_number_format', $clients->offer_number_format ?? ($clientSettings->offer_number_format ?? '')) == 'YYYY*1000+N' ? 'selected' : '' }}>Jahr*1000+Nummer (z.B. 2025001)</option>
                            <option value="YYYYNN" {{ old('offer_number_format', $clients->offer_number_format ?? ($clientSettings->offer_number_format ?? '')) == 'YYYYNN' ? 'selected' : '' }}>Jahr + Nummer (z.B. 20250001)</option>
                            <option value="YY*1000+N" {{ old('offer_number_format', $clients->offer_number_format ?? ($clientSettings->offer_number_format ?? '')) == 'YY*1000+N' ? 'selected' : '' }}>Jahr(2-stellig)*1000+Nummer (z.B. 25001)</option>
                            <option value="YYYY_MM+N" {{ old('offer_number_format', $clients->offer_number_format ?? ($clientSettings->offer_number_format ?? '')) == 'YYYY_MM+N' ? 'selected' : '' }}>Jahr_Monat+Nummer (z.B. 2025_01001)</option>
                            <option value="YYYY*10000+N+6000" {{ old('offer_number_format', $clients->offer_number_format ?? ($clientSettings->offer_number_format ?? '')) == 'YYYY*10000+N+6000' ? 'selected' : '' }}>Jahr*10000+(Nummer+6000) - Angebote (z.B. 20256001)</option>
                            <option value="N" {{ old('offer_number_format', $clients->offer_number_format ?? ($clientSettings->offer_number_format ?? '')) == 'N' ? 'selected' : '' }}>Nur fortlaufende Nummer (z.B. 1, 2, 3...)</option>
                        </select>
                        @error('offer_number_format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-600">Format für automatische Angebotsnummerierung</p>
                    </div>
                </div>
            </div>

            <!-- Präfixe -->
            <div class="border-t border-gray-200 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Präfixe</h3>
                <div class="grid md:grid-cols-2 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-1">
                        <x-input name="invoice_prefix" type="text" placeholder="R-" label="Rechnungs-Präfix" value="{{ old('invoice_prefix', $clients->invoice_prefix ?? ($clientSettings->invoice_prefix ?? '')) }}" />
                        <p class="mt-1 text-sm text-gray-600">Präfix für Rechnungsnummern (z.B. "R-", "RECH-")</p>
                    </div>

                    <div class="sm:col-span-1">
                        <x-input name="offer_prefix" type="text" placeholder="A-" label="Angebots-Präfix" value="{{ old('offer_prefix', $clients->offer_prefix ?? ($clientSettings->offer_prefix ?? '')) }}" />
                        <p class="mt-1 text-sm text-gray-600">Präfix für Angebotsnummern (z.B. "A-", "ANG-")</p>
                    </div>
                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6  px-4 py-4 sm:px-8">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 px-3 py-2 text-white hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-blue-900">
                    Einstellungen speichern
                </button>
            </div>
        </form>
    </div>

    <!-- Einbindung von Summernote -->
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#signature').summernote({
                    height: 300, // Höhe des Editors
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    placeholder: 'Geben Sie hier Ihre E-Mail Signatur ein...',
                });

                $('#document_footer').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    placeholder: 'Text, der in Angeboten/Rechnungen unter der Summe angezeigt wird...',
                });

                // Sicherstellen, dass der Editor-Inhalt beim Absenden im Textarea landet
                $('form[action="{{ route('clients.update-my-settings') }}"]').on('submit', function() {
                    const sig = $('#signature').summernote('code');
                    const docf = $('#document_footer').summernote('code');
                    $('#signature').val(sig);
                    $('#document_footer').val(docf);
                });
            });

            function previewImage(input) {
                const preview = document.getElementById('preview');
                const previewContainer = document.getElementById('logo-preview');
                
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = '#';
                    previewContainer.classList.add('hidden');
                }
            }
        </script>
    @endpush
</x-layout> 