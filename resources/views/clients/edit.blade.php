<x-layout>
    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <!-- Linke Spalte: Überschrift -->
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Klienten bearbeiten</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte aktualisieren Sie die Informationen des Klienten.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('clients.update', $clients->id) }}" method="POST" enctype="multipart/form-data" class="sm:col-span-1 md:col-span-5">
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
                    <x-input name="location" type="text" placeholder="Ort" label="Ort" value="{{ old('postalcode', $clients->location) }}" />
                </div>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Email -->
                <div class="sm:col-span-1">
                    <x-input name="email" type="text" placeholder="Email" label="Email" value="{{ old('postalcode', $clients->email) }}" />
                </div>

                <!-- Telefon -->
                <div class="sm:col-span-1">
                    <x-input name="phone" type="text" placeholder="Telefon" label="Telefon" value="{{ old('postalcode', $clients->phone) }}" />
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
                        <x-dropdown_body name="smallbusiness" label="Kleinunternehmer" :options="['1' => 'Ja','0' => 'Nein']" :selected="old('smallbusiness', $clients->smallbusiness)" placeholder="Bitte wählen..." required/>
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
            <input type="hidden" name="id" value="{{ $clients->id }}">

            <!-- Signatur -->
            <div class="border-b border-gray-900/10 pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Signatur</h3>
                <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                    <div class="sm:col-span-6">
                        <label for="signature" class="block text-sm font-medium text-gray-900">Signatur</label>
                        <div class="mt-2">
                            <textarea name="signature" id="signature" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">{{ old('signature', $clients->signature) }}</textarea>
                            @error('signature')
                                <span class="text-sm text-red-600">{{ $message }}</span>
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
                        <label for="logo" class="block text-sm font-medium text-gray-900">Logo</label>
                        <div class="mt-2 flex items-center gap-x-3">
                            <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/jpg,image/gif" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" onchange="previewImage(this)">
                            <div id="logo-preview" class="{{ $clients->logo ? '' : 'hidden' }}">
                                <img id="preview" src="{{ $clients->logo ? asset('storage/logos/' . $clients->logo) : '#' }}" alt="Logo Vorschau" class="h-20 w-auto object-contain">
                            </div>
                        </div>
                        @error('logo')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="sm:col-span-1">
                        <x-input name="logoheight" type="number" placeholder="Höhe" label="Höhe" value="{{ old('logoheight', $clients->logoheight) }}" />
                    </div>

                    <div class="sm:col-span-1">
                        <x-input name="logowidth" type="number" placeholder="Breite" label="Breite" value="{{ old('logowidth', $clients->logowidth) }}" />
                    </div>
                </div>
            </div>

            <!-- Nummerierung -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Nummerierung</h3>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input-label for="lastoffer" value="Letzte Angebotsnummer" />
                        <x-text-input id="lastoffer" name="lastoffer" type="number" class="mt-1 block w-full" :value="old('lastoffer', $clients->lastoffer)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('lastoffer')" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input-label for="offermultiplikator" value="Angebot Multiplikator" />
                        <x-text-input id="offermultiplikator" name="offermultiplikator" type="number" class="mt-1 block w-full" :value="old('offermultiplikator', $clients->offermultiplikator)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('offermultiplikator')" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input-label for="lastinvoice" value="Letzte Rechnungsnummer" />
                        <x-text-input id="lastinvoice" name="lastinvoice" type="number" class="mt-1 block w-full" :value="old('lastinvoice', $clients->lastinvoice)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('lastinvoice')" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input-label for="invoicemultiplikator" value="Rechnung Multiplikator" />
                        <x-text-input id="invoicemultiplikator" name="invoicemultiplikator" type="number" class="mt-1 block w-full" :value="old('invoicemultiplikator', $clients->invoicemultiplikator)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('invoicemultiplikator')" />
                    </div>

                    <div class="sm:col-span-3">
                        <x-input-label for="max_upload_size" value="Maximale Upload-Größe (MB)" />
                        <x-text-input id="max_upload_size" name="max_upload_size" type="number" class="mt-1 block w-full" :value="old('max_upload_size', $clients->max_upload_size)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('max_upload_size')" />
                    </div>
                </div>
            </div>

            <!-- Farbe -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Farbe</h3>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input-label for="color" value="Farbe für Rechnungen und Angebote" />
                        <x-text-input id="color" name="color" type="color" class="mt-1 block w-full h-12" :value="old('color', $clients->color)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('color')" />
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
