<x-layout>
    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <!-- Linke Spalte: Überschrift -->
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Klienten anlegen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die Informationen des Klienten.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('clients.create') }}" method="GET" class="sm:col-span-1 md:col-span-5">



            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                <!-- Name -->
                <div class="sm:col-span-1">
                    <x-input name="clientname" type="text" placeholder="Name" label="Name" value="" />
                </div>

                <!-- Firma -->
                <div class="sm:col-span-1">
                    <x-input name="companyname" type="text" placeholder="Firma" label="Firma" value="" />
                </div>

                <!-- Firmenart -->
                <div class="sm:col-span-1">
                    <x-input name="business" type="text" placeholder="Firmenart" label="Firmenart" value="" />
                </div>

                <!-- UID -->
                <div class="sm:col-span-1">
                    <x-input name="vat_number" type="text" placeholder="UID" label="UID" value="" />
                </div>
            </div>

            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Adresse -->
                <div class="sm:col-span-1">
                    <x-input name="address" type="text" placeholder="Adresse" label="Adresse" value="" />
                </div>

                <!-- Postleitzahl -->
                <div class="sm:col-span-1">
                    <x-input name="postalcode" type="text" placeholder="Postleitzahl" label="Postleitzahl" value="" />
                </div>

                <!-- Ort -->
                <div class="sm:col-span-1">
                    <x-input name="location" type="text" placeholder="Ort" label="Ort" value="" />
                </div>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Email -->
                <div class="sm:col-span-1">
                    <x-input name="email" type="text" placeholder="Email" label="Email" value="" />
                </div>

                <!-- Telefon -->
                <div class="sm:col-span-1">
                    <x-input name="phone" type="text" placeholder="Telefon" label="Telefon" value="" />
                </div>
            </div>

            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Konditionen -->
                <div class="sm:col-span-1">
                    <x-dropdown_body name="tax_id" id="tax_id" value="" :options="$taxrates->pluck('taxrate', 'id')" :selected="1" label="Steuerhöhe" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                </div>
                <div class="sm:col-span-1">
                    <x-dropdown_body name="smallbusiness" label="Kleinunternehmer" :options="['1' => 'Ja','0' => 'Nein']" :selected="1" placeholder="Bitte wählen..." required/>
                </div>
            </div>

            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <div class="sm:col-span-1">
                    <x-input name="webpage" type="text" placeholder="Web-Seite" label="Web-Seite" value="" />
                </div>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <div class="sm:col-span-1">
                    <x-input name="bank" type="text" placeholder="Bankname" label="Bankname" value="" />
                </div>
                <div class="sm:col-span-1">
                    <x-input name="accountnumber" type="text" placeholder="Kontonummer" label="Kontonummer" value="" />
                </div>
                <div class="sm:col-span-1">
                    <x-input name="bic" type="text" placeholder="BIC" label="BIC" value="" />
                </div>
            </div>

            <!-- Versteckte Felder -->
            <input type="hidden" name="style" value="">
            <input type="hidden" name="id" value="">

            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Signatur mit Summernote -->
                <div class="sm:col-span-6">
                    <label for="signature" class="block text-sm font-medium text-gray-900">Signatur</label>
                    <div class="mt-2">
                        <textarea name="signature" id="signature" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600"></textarea>
                        @error('signature')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-5 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <div class="sm:col-span-1">
                    <x-input name="logo" type="text" placeholder="Logo" label="Logo" value="" />
                </div>


                <div class="sm:col-span-1">
                    <x-input name="logoheight" type="text" placeholder="Höhe" label="Höhe" value="" />
                </div>

                <div class="sm:col-span-1">
                    <x-input name="logowidth" type="text" placeholder="Breite" label="Breite" value="" />
                </div>
            </div>

            <div class="grid md:grid-cols-5 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <div class="sm:col-span-1">
                    <x-input name="lastoffer" type="text" placeholder="Letzte Angebotsnummer" label="Letzte Angebotsnummer" value="" />
                </div>
                <div class="sm:col-span-1">
                    <x-input name="offermultiplikator" type="text" placeholder="Multiplikator Angebot" label="Multiplikator Angebot" value="" />
                </div>

                <div class="sm:col-span-1">
                    <x-input name="lastinvoice" type="text" placeholder="Letzte Rechnungsnummer" label="Letzte Rechnungsnummer" value="" />
                </div>
                <div class="sm:col-span-1">
                    <x-input name="invoicemultiplikator" type="text" placeholder="Multiplikator Rechnung" label="Multiplikator Rechnung" value="" />
                </div>
                <div class="sm:col-span-1">
                    <x-input name="max_upload_size" type="text" placeholder="Max. Dateigröße" label="Max. Dateigröße" value="" />
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
                    height: 150, // Höhe des Editors
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
        </script>
    @endpush
</x-layout>
