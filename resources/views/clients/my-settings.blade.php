<x-layout>

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

            <!-- Farbe -->
            <div class="pb-6">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Farbe</h3>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input name="color" type="color" placeholder="Farbe für Rechnungen und Angebote" label="Farbe für Rechnungen und Angebote" value="{{ old('color', $clients->color) }}" />
                    </div>
                </div>
            </div>

            <!-- Nummerierung & Präfixe -->
            <div class="border-t border-gray-200">
                <h3 class="text-base font-semibold leading-7 text-gray-900 mb-2">Nummerierung & Präfixe</h3>
                <p class="text-sm text-gray-600">Die Nummerierung (letzte Angebots-/Rechnungsnummer, Formate, Präfixe) wird in den <strong>Statischen Einstellungen</strong> verwaltet.</p>
                @php($user = Auth::user())
                @if($user && $user->hasPermission('edit_client_settings'))
                    <div class="mt-3">
                        <a href="{{ route('client-settings.edit') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600">
                            Zu den Statischen Einstellungen
                        </a>
                    </div>
                @else
                    <p class="mt-2 text-sm text-gray-500">Ihnen fehlt die Berechtigung <code>edit_client_settings</code>, um die Nummern-Einstellungen zu ändern.</p>
                @endif
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