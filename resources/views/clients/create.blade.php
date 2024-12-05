<x-layout>
    <!-- Fehlermeldungen -->
    @if($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif

    <!-- Erfolgsnachricht -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
        <!-- Linke Spalte: Überschrift -->
        <div class="px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Neuen Klienten erstellen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die folgenden Informationen aus, um einen neuen Klienten anzulegen.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('clients.store') }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            @csrf

            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <!-- Name -->
                    <div class="sm:col-span-3">
                        <label for="clientname" class="block text-sm font-medium text-gray-900">Name</label>
                        <div class="mt-2">
                            <input type="text" name="clientname" id="clientname" value="{{ old('clientname') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('clientname')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Firma -->
                    <div class="sm:col-span-3">
                        <label for="companyname" class="block text-sm font-medium text-gray-900">Firma</label>
                        <div class="mt-2">
                            <input type="text" name="companyname" id="companyname" value="{{ old('companyname') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('companyname')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Firmenart -->
                    <div class="sm:col-span-3">
                        <label for="business" class="block text-sm font-medium text-gray-900">Firmenart</label>
                        <div class="mt-2">
                            <input type="text" name="business" id="business" value="{{ old('business') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('business')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- UID -->
                    <div class="sm:col-span-3">
                        <label for="vat_number" class="block text-sm font-medium text-gray-900">UID</label>
                        <div class="mt-2">
                            <input type="text" name="vat_number" id="vat_number" value="{{ old('vat_number') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('vat_number')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="sm:col-span-3">
                        <label for="address" class="block text-sm font-medium text-gray-900">Adresse</label>
                        <div class="mt-2">
                            <input type="text" name="address" id="address" value="{{ old('address') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('address')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Postleitzahl -->
                    <div class="sm:col-span-2">
                        <label for="postalcode" class="block text-sm font-medium text-gray-900">Postleitzahl</label>
                        <div class="mt-2">
                            <input type="text" name="postalcode" id="postalcode" value="{{ old('postalcode') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('postalcode')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Ort -->
                    <div class="sm:col-span-1">
                        <label for="location" class="block text-sm font-medium text-gray-900">Ort</label>
                        <div class="mt-2">
                            <input type="text" name="location" id="location" value="{{ old('location') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('location')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                        <div class="mt-2">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('email')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Telefon -->
                    <div class="sm:col-span-2">
                        <label for="phone" class="block text-sm font-medium text-gray-900">Telefon</label>
                        <div class="mt-2">
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('phone')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Steuerhöhe -->
                    <div class="sm:col-span-2">
                        <label for="tax_id" class="block text-sm font-medium text-gray-900">Steuerhöhe</label>
                        <div class="mt-2">
                            <select name="tax_id" id="tax_id" required class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 focus:outline-indigo-600">
                                <option value="">Bitte wählen...</option>
                                @foreach ($taxrates as $taxrate)
                                    <option value="{{ $taxrate->id }}" {{ old('tax_id') == $taxrate->id ? 'selected' : '' }}>
                                        {{ $taxrate->taxrate }}%
                                    </option>
                                @endforeach
                            </select>
                            @error('tax_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Web-Seite -->
                    <div class="sm:col-span-3">
                        <label for="webpage" class="block text-sm font-medium text-gray-900">Web-Seite</label>
                        <div class="mt-2">
                            <input type="text" name="webpage" id="webpage" value="{{ old('webpage') }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('webpage')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Bankname -->
                    <div class="sm:col-span-3">
                        <label for="bank" class="block text-sm font-medium text-gray-900">Bankname</label>
                        <div class="mt-2">
                            <input type="text" name="bank" id="bank" value="{{ old('bank') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('bank')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Kontonummer -->
                    <div class="sm:col-span-3">
                        <label for="accountnumber" class="block text-sm font-medium text-gray-900">Kontonummer</label>
                        <div class="mt-2">
                            <input type="text" name="accountnumber" id="accountnumber" value="{{ old('accountnumber') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('accountnumber')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- BIC -->
                    <div class="sm:col-span-3">
                        <label for="bic" class="block text-sm font-medium text-gray-900">BIC</label>
                        <div class="mt-2">
                            <input type="text" name="bic" id="bic" value="{{ old('bic') }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('bic')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Kleinunternehmer -->
                    <div class="sm:col-span-3">
                        <label for="smallbusiness" class="block text-sm font-medium text-gray-900">Kleinunternehmer</label>
                        <div class="mt-2">
                            <select name="smallbusiness" id="smallbusiness" required class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 focus:outline-indigo-600">
                                <option value="">Bitte wählen...</option>
                                <option value="1" {{ old('smallbusiness') == '1' ? 'selected' : '' }}>Ja</option>
                                <option value="0" {{ old('smallbusiness') == '0' ? 'selected' : '' }}>Nein</option>
                            </select>
                            @error('smallbusiness')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Signatur mit Summernote -->
                    <div class="sm:col-span-6">
                        <label for="signature" class="block text-sm font-medium text-gray-900">Signatur</label>
                        <div class="mt-2">
                            <textarea name="signature" id="signature" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">{{ old('signature') }}</textarea>
                            @error('signature')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="sm:col-span-2">
                        <label for="logo" class="block text-sm font-medium text-gray-900">Logo</label>
                        <div class="mt-2">
                            <input type="text" name="logo" id="logo" value="{{ old('logo') }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('logo')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Höhe -->
                    <div class="sm:col-span-2">
                        <label for="logoheight" class="block text-sm font-medium text-gray-900">Höhe</label>
                        <div class="mt-2">
                            <input type="number" name="logoheight" id="logoheight" value="{{ old('logoheight', 100) }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('logoheight')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Breite -->
                    <div class="sm:col-span-2">
                        <label for="logowidth" class="block text-sm font-medium text-gray-900">Breite</label>
                        <div class="mt-2">
                            <input type="number" name="logowidth" id="logowidth" value="{{ old('logowidth', 100) }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('logowidth')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Letzte Angebotsnummer -->
                    <div class="sm:col-span-2">
                        <label for="lastoffer" class="block text-sm font-medium text-gray-900">Letzte Angebotsnummer</label>
                        <div class="mt-2">
                            <input type="number" name="lastoffer" id="lastoffer" value="{{ old('lastoffer', 1) }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('lastoffer')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Multiplikator Angebot -->
                    <div class="sm:col-span-2">
                        <label for="offermultiplikator" class="block text-sm font-medium text-gray-900">Multiplikator Angebot</label>
                        <div class="mt-2">
                            <input type="number" name="offermultiplikator" id="offermultiplikator" value="{{ old('offermultiplikator', 10000) }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('offermultiplikator')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Letzte Rechnungsnummer -->
                    <div class="sm:col-span-2">
                        <label for="lastinvoice" class="block text-sm font-medium text-gray-900">Letzte Rechnungsnummer</label>
                        <div class="mt-2">
                            <input type="number" name="lastinvoice" id="lastinvoice" value="{{ old('lastinvoice', 1) }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('lastinvoice')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Multiplikator Rechnung -->
                    <div class="sm:col-span-2">
                        <label for="invoicemultiplikator" class="block text-sm font-medium text-gray-900">Multiplikator Rechnung</label>
                        <div class="mt-2">
                            <input type="number" name="invoicemultiplikator" id="invoicemultiplikator" value="{{ old('invoicemultiplikator', 10000) }}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('invoicemultiplikator')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Verstecktes Feld für style -->
                    <input type="hidden" name="style" value="{{ old('style') }}">
                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('clients.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
                    Klient erstellen
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
                    placeholder: 'Geben Sie hier Ihre Signatur ein...',
                    tabsize: 2,
                    height: 120
                });
            });
        </script>
    @endpush
</x-layout>
