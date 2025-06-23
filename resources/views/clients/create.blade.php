<x-layout>
    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <!-- Linke Spalte: Überschrift -->
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Klienten anlegen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die Informationen des Klienten.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data" class="sm:col-span-1 md:col-span-5">
            @csrf

            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                <!-- Name -->
                <div class="sm:col-span-1">
                    <x-input name="clientname" type="text" placeholder="Name" label="Name" value="{{ old('clientname') }}"  />
                </div>

                <!-- Firma -->
                <div class="sm:col-span-1">
                    <x-input name="companyname" type="text" placeholder="Firma" label="Firma" value="{{ old('companyname') }}"  />
                </div>

                <!-- Firmenart -->
                <div class="sm:col-span-1">
                    <x-input name="business" type="text" placeholder="Firmenart" label="Firmenart" value="{{ old('business') }}"  />
                </div>

                <!-- UID -->
                <div class="sm:col-span-1">
                    <x-input name="vat_number" type="text" placeholder="UID" label="UID" value="{{ old('vat_number') }}" />
                </div>
            </div>

            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Adresse -->
                <div class="sm:col-span-1">
                    <x-input name="address" type="text" placeholder="Adresse" label="Adresse" value="{{ old('address') }}"  />
                </div>

                <!-- Postleitzahl -->
                <div class="sm:col-span-1">
                    <x-input name="postalcode" type="text" placeholder="Postleitzahl" label="Postleitzahl" value="{{ old('postalcode') }}"  />
                </div>

                <!-- Ort -->
                <div class="sm:col-span-1">
                    <x-input name="location" type="text" placeholder="Ort" label="Ort" value="{{ old('location') }}"  />
                </div>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Email -->
                <div class="sm:col-span-1">
                    <x-input name="email" type="email" placeholder="Email" label="Email" value="{{ old('email') }}"  />
                </div>

                <!-- Telefon -->
                <div class="sm:col-span-1">
                    <x-input name="phone" type="text" placeholder="Telefon" label="Telefon" value="{{ old('phone') }}"  />
                </div>
            </div>

            <!-- Steuerliche Informationen -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Steuerliche Informationen</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <!-- Konditionen -->
                    <div class="sm:col-span-1">
                        <x-dropdown_body name="tax_id" id="tax_id" value="{{ old('tax_id') }}" :options="$taxrates->pluck('taxrate', 'id')" :selected="old('tax_id', 1)" label="Steuerhöhe" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"  />
                    </div>
                    <div class="sm:col-span-1">
                        <x-dropdown_body name="smallbusiness" label="Kleinunternehmer" :options="['1' => 'Ja','0' => 'Nein']" :selected="old('smallbusiness', 1)" placeholder="Bitte wählen..." />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="tax_number" type="text" placeholder="Steuernummer" label="Steuernummer" value="{{ old('tax_number') }}" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="company_registration_number" type="text" placeholder="Handelsregisternummer" label="Handelsregisternummer" value="{{ old('company_registration_number') }}" />
                    </div>
                </div>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-1">
                        <x-input name="management" type="text" placeholder="Geschäftsführung" label="Geschäftsführung" value="{{ old('management') }}" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="regional_court" type="text" placeholder="Handelsregistergericht" label="Handelsregistergericht" value="{{ old('regional_court') }}" />
                    </div>
                </div>
            </div>

            <!-- Webseite -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Webseite</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-1">
                        <x-input name="webpage" type="text" placeholder="Web-Seite" label="Web-Seite" value="{{ old('webpage') }}" />
                    </div>
                </div>
            </div>

            <!-- Bankverbindung -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Bankverbindung</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-1">
                        <x-input name="bank" type="text" placeholder="Bankname" label="Bankname" value="{{ old('bank') }}"  />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="accountnumber" type="text" placeholder="Kontonummer" label="Kontonummer" value="{{ old('accountnumber') }}"  />
                    </div>
                    <div class="sm:col-span-1">
                        <x-input name="bic" type="text" placeholder="BIC" label="BIC" value="{{ old('bic') }}"  />
                    </div>
                </div>
            </div>

            <!-- Versteckte Felder -->
            <input type="hidden" name="style" value="{{ old('style') }}">
            <input type="hidden" name="id" value="{{ old('id') }}">

            <!-- Signatur -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Signatur</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-6">
                        <label for="signature" class="block text-sm/6 font-medium text-gray-900 mb-1">Signatur</label>
                        <div class="mt-1">
                            <textarea name="signature" id="signature" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('signature') border-red-500 outline-red-500 @enderror">{{ old('signature') }}</textarea>
                            @error('signature')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Logo</h3>
                <div class="grid md:grid-cols-5 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-2">
                        <label for="logo" class="block text-sm/6 font-medium text-gray-900 mb-1">Logo</label>
                        <div class="mt-1 flex items-center gap-x-3">
                            <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/jpg,image/gif" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none @error('logo') border-red-500 @enderror" onchange="previewImage(this)">
                            <div id="logo-preview" class="hidden">
                                <img id="preview" src="#" alt="Logo Vorschau" class="h-20 w-auto object-contain">
                            </div>
                        </div>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-1">
                        <x-input name="logoheight" type="number" placeholder="Höhe" label="Höhe" value="{{ old('logoheight') }}" />
                    </div>

                    <div class="sm:col-span-1">
                        <x-input name="logowidth" type="number" placeholder="Breite" label="Breite" value="{{ old('logowidth') }}" />
                    </div>
                </div>
            </div>

            <!-- Nummerierung -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Nummerierung</h3>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input name="lastoffer" type="number" placeholder="Letzte Angebotsnummer" label="Letzte Angebotsnummer" value="{{ old('lastoffer', $client->lastoffer ?? 0) }}"  />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="offermultiplikator" type="number" placeholder="Angebot Multiplikator" label="Angebot Multiplikator" value="{{ old('offermultiplikator', $client->offermultiplikator ?? 1) }}"  />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="lastinvoice" type="number" placeholder="Letzte Rechnungsnummer" label="Letzte Rechnungsnummer" value="{{ old('lastinvoice', $client->lastinvoice ?? 0) }}"  />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="invoicemultiplikator" type="number" placeholder="Rechnung Multiplikator" label="Rechnung Multiplikator" value="{{ old('invoicemultiplikator', $client->invoicemultiplikator ?? 1) }}"  />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="max_upload_size" type="number" placeholder="Maximale Upload-Größe (MB)" label="Maximale Upload-Größe (MB)" value="{{ old('max_upload_size', $client->max_upload_size ?? 10) }}"  />
                    </div>

                    <div class="sm:col-span-6">
                        <label for="invoice_number_format" class="block text-sm/6 font-medium text-gray-900 mb-1">Rechnungsnummer-Format</label>
                        <select name="invoice_number_format" id="invoice_number_format" class="mt-1 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('invoice_number_format') border-red-500 outline-red-500 @enderror">
                            <option value="YYYY*1000+N" {{ old('invoice_number_format') == 'YYYY*1000+N' ? 'selected' : '' }}>Jahr*1000+Nummer (z.B. 2025001)</option>
                            <option value="YYYYNN" {{ old('invoice_number_format') == 'YYYYNN' ? 'selected' : '' }}>Jahr + Nummer (z.B. 20250001)</option>
                            <option value="YY*1000+N" {{ old('invoice_number_format') == 'YY*1000+N' ? 'selected' : '' }}>Jahr(2-stellig)*1000+Nummer (z.B. 25001)</option>
                            <option value="YYYY_MM+N" {{ old('invoice_number_format') == 'YYYY_MM+N' ? 'selected' : '' }}>Jahr_Monat+Nummer (z.B. 2025_01001)</option>
                            <option value="N" {{ old('invoice_number_format') == 'N' ? 'selected' : '' }}>Nur fortlaufende Nummer (z.B. 1, 2, 3...)</option>
                            <option value="custom" {{ old('invoice_number_format') == 'custom' ? 'selected' : '' }}>Benutzerdefiniert</option>
                        </select>
                        @error('invoice_number_format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-600">Wählen Sie das Format für die automatische Rechnungsnummerierung.</p>
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="invoice_prefix" type="text" placeholder="R-" label="Rechnungs-Präfix" value="{{ old('invoice_prefix') }}" />
                        <p class="mt-1 text-sm text-gray-600">Präfix für Rechnungsnummern (z.B. "R-", "RECH-")</p>
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="offer_prefix" type="text" placeholder="A-" label="Angebots-Präfix" value="{{ old('offer_prefix') }}" />
                        <p class="mt-1 text-sm text-gray-600">Präfix für Angebotsnummern (z.B. "A-", "ANG-")</p>
                    </div>
                </div>
            </div>

            <!-- Farbe -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Farbe</h3>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input name="color" type="color" placeholder="Farbe für Rechnungen und Angebote" label="Farbe für Rechnungen und Angebote" value="{{ old('color', $client->color ?? '#000000') }}" />
                    </div>
                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6  px-4 py-4 sm:px-8">
                <a href="{{ route('clients.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
                    Änderungen speichern
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
                    height: 150,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    placeholder: 'Geben Sie hier Ihre Signatur ein...',
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
