<x-layout>
    @if($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif

    <div class="container">
        <!-- Erfolgsnachricht -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('clients.update', $clients->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Kundeninformationen -->
            <div class="form-row mt-4">
                <div class="form-group col-md-3">
                    <label for="clientname">Name</label>
                    <input type="text" class="form-control" id="clientname" name="clientname" value="{{ old('clientname', $clients->clientname) }}" required>
                    @error('clientname')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="companyname">Firma</label>
                    <input type="text" class="form-control" id="companyname" name="companyname" value="{{ old('companyname', $clients->companyname) }}" required>
                    @error('companyname')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="business">Firmenart</label>
                    <input type="text" class="form-control" id="business" name="business" value="{{ old('business', $clients->business) }}" required>
                    @error('business')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="vat_number">UID</label>
                    <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{ old('vat_number', $clients->vat_number) }}">
                    @error('vat_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Adresse -->
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="address">Adresse</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $clients->address) }}" required>
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="postalcode">Postleitzahl</label>
                    <input type="text" class="form-control" id="postalcode" name="postalcode" value="{{ old('postalcode', $clients->postalcode) }}" required>
                    @error('postalcode')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="location">Ort</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $clients->location) }}" required>
                    @error('location')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Kontaktinformationen -->
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $clients->email) }}" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="phone">Telefon</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $clients->phone) }}" required>
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="tax_id">Steuerhöhe</label>
                    <select class="form-control" id="tax_id" name="tax_id" required>
                        <option value="">Bitte wählen...</option>
                        @foreach ($taxrates as $taxrate)
                            <option value="{{ $taxrate->id }}" {{ old('tax_id', $clients->tax_id) == $taxrate->id ? 'selected' : '' }}>
                                {{ $taxrate->taxrate }}%
                            </option>
                        @endforeach
                    </select>
                    @error('tax_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Weitere Informationen -->
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="webpage">Web-Seite</label>
                    <input type="text" class="form-control" id="webpage" name="webpage" value="{{ old('webpage', $clients->webpage) }}">
                    @error('webpage')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Bankinformationen -->
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="bank">Bankname</label>
                    <input type="text" class="form-control" id="bank" name="bank" value="{{ old('bank', $clients->bank) }}" required>
                    @error('bank')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="accountnumber">Kontonummer</label>
                    <input type="text" class="form-control" id="accountnumber" name="accountnumber" value="{{ old('accountnumber', $clients->accountnumber) }}" required>
                    @error('accountnumber')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="bic">BIC</label>
                    <input type="text" class="form-control" id="bic" name="bic" value="{{ old('bic', $clients->bic) }}" required>
                    @error('bic')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Umgewandelte Felder -->
            <div class="form-row mt-4">
                <!-- smallbusiness als Dropdown -->
                <div class="form-group col-md-3">
                    <label for="smallbusiness">Kleinunternehmer</label>
                    <select class="form-control" id="smallbusiness" name="smallbusiness" required>
                        <option value="">Bitte wählen...</option>
                        <option value="1" {{ old('smallbusiness', $clients->smallbusiness) == 1 ? 'selected' : '' }}>Ja</option>
                        <option value="0" {{ old('smallbusiness', $clients->smallbusiness) == 0 ? 'selected' : '' }}>Nein</option>
                    </select>
                    @error('smallbusiness')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- style bleibt als verstecktes Feld -->
                <input type="hidden" name="style" value="{{ old('style', $clients->style) }}">

                <!-- id bleibt als verstecktes Feld -->
                <input type="hidden" name="id" value="{{ $clients->id }}">

                <!-- signature als textarea -->
                <div class="form-group col-md-6">
                    <label for="signature">Signatur</label>
                    <textarea class="form-control summernote" id="signature" name="signature" rows="3">
                        {{ old('signature', $clients->signature) }}
                    </textarea>
                    @error('signature')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Logo -->
            <hr>
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="logo">Logo</label>
                    <input type="text" class="form-control" id="logo" name="logo" value="{{ old('logo', $clients->logo) }}">
                    @error('logo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="logoheight">Höhe</label>
                    <input type="number" class="form-control" id="logoheight" name="logoheight" value="{{ old('logoheight', $clients->logoheight) }}">
                    @error('logoheight')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="logowidth">Breite</label>
                    <input type="number" class="form-control" id="logowidth" name="logowidth" value="{{ old('logowidth', $clients->logowidth) }}">
                    @error('logowidth')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Letzte Nummern -->
            <div class="form-row mt-4">
                <div class="form-group col">
                    <label for="lastoffer">Letzte Angebotsnummer</label>
                    <input type="number" class="form-control" id="lastoffer" name="lastoffer" value="{{ old('lastoffer', $clients->lastoffer) }}" required>
                    @error('lastoffer')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col">
                    <label for="offermultiplikator">Multiklikator Angebot</label>
                    <input type="number" class="form-control" id="offermultiplikator" name="offermultiplikator" value="{{ old('offermultiplikator', $clients->offermultiplikator) }}" required>
                    @error('offermultiplikator')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col">
                    <label for="lastinvoice">Letzte Rechnungsnummer</label>
                    <input type="number" class="form-control" id="lastinvoice" name="lastinvoice" value="{{ old('lastinvoice', $clients->lastinvoice) }}" required>
                    @error('lastinvoice')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="invoicemultiplikator">Multiklikator Rechnung</label>
                    <input type="number" class="form-control" id="invoicemultiplikator" name="invoicemultiplikator" value="{{ old('invoicemultiplikator', $clients->invoicemultiplikator) }}" required>
                    @error('invoicemultiplikator')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Submit-Button -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Änderungen speichern</button>
            </div>
        </form>
        <hr>
    </div>

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

</x-layout>
