<x-layout>
    <!-- Versionshinweis (Ausklapbar) -->
    <div class="mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg overflow-hidden">
            <!-- Kompakte Anzeige -->
            <button type="button" 
                    class="w-full p-3 flex justify-between items-center hover:bg-blue-100 transition-colors"
                    onclick="toggleVersionInfo()">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-4 w-4 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-2">
                        <span class="text-sm font-medium text-blue-800">
                            Version {{ $clients->version }}
                            @if($clients->is_active)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 ml-1">
                                    Aktiv
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-blue-600">Details anzeigen</span>
                    <svg id="version-chevron" class="h-4 w-4 text-blue-500 transform transition-transform duration-200" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>
            
            <!-- Erweiterte Details (standardmäßig versteckt) -->
            <div id="version-details" class="hidden border-t border-blue-200 bg-blue-25 p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Versionsinformationen</h4>
                        <div class="space-y-1 text-sm text-blue-700">
                            <p><span class="font-medium">Aktuelle Version:</span> {{ $clients->version }}</p>
                            <p><span class="font-medium">Gültig seit:</span> {{ $clients->valid_from->format('d.m.Y H:i') }}</p>
                            @if(!$clients->is_active)
                                <p><span class="font-medium">Status:</span> <span class="text-amber-600">Archiviert</span></p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex flex-col justify-center">
                        <div class="space-y-2">
                            <a href="{{ route('clients.versions', $clients) }}" 
                               class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Versionshistorie anzeigen
                            </a>
                            
                            <div class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded text-center">
                                <span class="font-medium">Hinweis:</span> Änderungen erstellen Version {{ $clients->version + 1 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleVersionInfo() {
        const details = document.getElementById('version-details');
        const chevron = document.getElementById('version-chevron');
        
        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
            chevron.style.transform = 'rotate(180deg)';
        } else {
            details.classList.add('hidden');
            chevron.style.transform = 'rotate(0deg)';
        }
    }
    </script>

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

            <!-- Signatur -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Signatur</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-6">
                        <label for="signature" class="block text-sm/6 font-medium text-gray-900 mb-1">Signatur</label>
                        <div class="mt-1">
                            <textarea name="signature" id="signature" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('signature') border-red-500 outline-red-500 @enderror">{{ old('signature', $clients->signature) }}</textarea>
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
                        @if($clients->logo)
                            <div class="mt-1">
                                <p class="text-sm font-medium text-gray-900 mb-2">Aktuelles Logo:</p>
                                <img src="{{ asset('storage/logos/' . $clients->logo) }}" alt="Aktuelles Logo" class="h-20 w-auto object-contain border rounded">
                            </div>
                        @endif
                        <div id="logo-preview" class="hidden mt-2">
                            <p class="text-sm font-medium text-gray-900 mb-2">Neues Logo:</p>
                            <img id="preview" src="#" alt="Logo Vorschau" class="h-20 w-auto object-contain border rounded">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nummerierung -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Nummerierung</h3>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input name="lastoffer" type="number" placeholder="Letzte Angebotsnummer" label="Letzte Angebotsnummer" value="{{ old('lastoffer', $clients->lastoffer) }}" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="offermultiplikator" type="number" placeholder="Angebot Multiplikator" label="Angebot Multiplikator" value="{{ old('offermultiplikator', $clients->offermultiplikator) }}" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="lastinvoice" type="number" placeholder="Letzte Rechnungsnummer" label="Letzte Rechnungsnummer" value="{{ old('lastinvoice', $clients->lastinvoice) }}" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="invoicemultiplikator" type="number" placeholder="Rechnung Multiplikator" label="Rechnung Multiplikator" value="{{ old('invoicemultiplikator', $clients->invoicemultiplikator) }}" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="max_upload_size" type="number" placeholder="Maximale Upload-Größe (MB)" label="Maximale Upload-Größe (MB)" value="{{ old('max_upload_size', $clients->max_upload_size) }}" />
                    </div>

                    <div class="sm:col-span-6">
                        <label for="invoice_number_format" class="block text-sm/6 font-medium text-gray-900 mb-1">Rechnungsnummer-Format</label>
                        <select name="invoice_number_format" id="invoice_number_format" class="mt-1 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('invoice_number_format') border-red-500 outline-red-500 @enderror">
                            <option value="YYYY*1000+N" {{ old('invoice_number_format', $clients->invoice_number_format) == 'YYYY*1000+N' ? 'selected' : '' }}>Jahr*1000+Nummer (z.B. 2025001)</option>
                            <option value="YYYYNN" {{ old('invoice_number_format', $clients->invoice_number_format) == 'YYYYNN' ? 'selected' : '' }}>Jahr + Nummer (z.B. 20250001)</option>
                            <option value="YY*1000+N" {{ old('invoice_number_format', $clients->invoice_number_format) == 'YY*1000+N' ? 'selected' : '' }}>Jahr(2-stellig)*1000+Nummer (z.B. 25001)</option>
                            <option value="YYYY_MM+N" {{ old('invoice_number_format', $clients->invoice_number_format) == 'YYYY_MM+N' ? 'selected' : '' }}>Jahr_Monat+Nummer (z.B. 2025_01001)</option>
                            <option value="N" {{ old('invoice_number_format', $clients->invoice_number_format) == 'N' ? 'selected' : '' }}>Nur fortlaufende Nummer (z.B. 1, 2, 3...)</option>
                            <option value="custom" {{ old('invoice_number_format', $clients->invoice_number_format) == 'custom' ? 'selected' : '' }}>Benutzerdefiniert</option>
                        </select>
                        @error('invoice_number_format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-600">Wählen Sie das Format für die automatische Rechnungsnummerierung.</p>
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="invoice_prefix" type="text" placeholder="R-" label="Rechnungs-Präfix" value="{{ old('invoice_prefix', $clients->invoice_prefix) }}" />
                        <p class="mt-1 text-sm text-gray-600">Präfix für Rechnungsnummern (z.B. "R-", "RECH-")</p>
                    </div>

                    <div class="sm:col-span-3">
                        <x-input name="offer_prefix" type="text" placeholder="A-" label="Angebots-Präfix" value="{{ old('offer_prefix', $clients->offer_prefix) }}" />
                        <p class="mt-1 text-sm text-gray-600">Präfix für Angebotsnummern (z.B. "A-", "ANG-")</p>
                    </div>
                </div>
            </div>

            <!-- Farbe -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Farbe</h3>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input name="color" type="color" placeholder="Farbe für Rechnungen und Angebote" label="Farbe für Rechnungen und Angebote" value="{{ old('color', $clients->color) }}" />
                    </div>
                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6  px-4 py-4 sm:px-8">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
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