<x-layout>

    <div class="container">
        <h3 class="mb-4">Kunde bearbeiten</h3>

        <div class="row mb-4">
            <div class="col text-right">
                <a href="{{ route('customer.index') }}" class="btn btn-transparent my-1">Zurück</a>
            </div>
        </div>

        <form action="{{ route('customer.update', $customer->id) }}" method="POST" class = "customer">
            @csrf
            @method('PUT')

            <!-- Anrede und Titel -->
            <div class="form-row mt-4">
                <div class="form-group col-md-4">

                    <label for="salutation_id">Anrede</label>
                    <select class="form-control" id="salutation_id" name="salutation_id">

                        @foreach($salutations as $salutation)
                            <option value="{{ $salutation->id }}" {{ $salutation->id == old('salutation_id', $customer->salutation_id) ? 'selected' : '' }}>
                                {{ $salutation->name }}
                            </option>
                        @endforeach
                    </select>

                </div>
                <div class="form-group col-md-4">
                    <label for="customername">Titel</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{$customer->title}}">
                </div>
            </div>

            <!-- Kundenname und Firmenname -->
            <div class="form-row mt-4">
                <div class="form-group col-md-6">
                    <label for="customername">Kundenname</label>
                    <input type="hidden" id="customerid" name="customerid" value="{{$customer->id}}">
                    <input type="text" class="form-control" id="customername" name="customername" value="{{ old('customername', $customer->customername) }}">
                    @error('customername')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="companyname">Firmenname</label>
                    <input type="text" class="form-control" id="companyname" name="companyname" value="{{$customer->companyname}}">
                </div>
            </div>

            <!-- Adresse, Postleitzahl und Ort -->
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="address">Adresse</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{$customer->address}}">
                </div>
                <div class="form-group col-md-4">
                    <label for="postalcode">Postleitzahl</label>
                    <input type="text" class="form-control" id="postalcode" name="postalcode" value="{{$customer->postalcode}}">
                </div>
                <div class="form-group col-md-4">
                    <label for="location">Ort</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{$customer->location}}">
                </div>
            </div>
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="address">Land</label>
                    <input type="text" class="form-control" id="country" name="country" value="{{$customer->country}}">
                </div>
                <div class="form-group col-md-4">
                </div>
                <div class="form-group col-md-4">
                </div>
            </div>

            <!-- UID -->
            <div class="form-row mt-4">
                <div class="form-group col-md-6">
                    <label for="tax_id">Kondition</label>
                    <select class="form-control" id="tax_id" name="tax_id">
                        <option value="">Bitte wählen...</option>
                        @foreach ($taxrates as $taxrate)
                            <option value="{{ $taxrate->id }}"
                                @if ($customer->tax_id == $taxrate->id) selected @endif>
                                {{ $taxrate->taxrate }}%
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Telefonnummer, Fax und E-Mail -->
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="phonenumber">Telefonnummer</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{$customer->phone}}">
                </div>
                <div class="form-group col-md-4">
                    <label for="fax">Fax</label>
                    <input type="text" class="form-control" id="fax" name="fax" value="{{$customer->fax}}">
                </div>
                <div class="form-group col-md-4">
                    <label for="email">E-Mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{$customer->email}}">
                </div>
            </div>

            <!-- Dropdown-Menü für Bedingungen -->
            <div class="form-row mt-4">
                <div class="form-group col-md-6">
                    <label for="condition_id">Konditionen</label>
                    <select class="form-control" id="condition_id" name="condition_id">
                        @foreach($conditions as $condition)
                            <option value="{{ $condition->id }}" {{ $condition->id == old('condition_id', $customer->condition_id) ? 'selected' : '' }}>
                                {{ $condition->conditionname }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Email-Betreff und Nachricht -->
            <hr>
            <div class="form-row mt-4">
                <div class="form-group col-md-4">
                    <label for="emailsubject">Email-Betreff</label>
                    <span class="info-icon" id="info-icon">i</span>
                    <input type="text" class="form-control" id="emailsubject" name="emailsubject" value="{{$customer->emailsubject}}">
                </div>
            </div>

            <div class="form-row mt-4">
                <div class="form-group">
                    <label for="emailbody">Nachricht:</label>
                    <textarea class="form-control summernote" id="emailbody" name="emailbody" rows="7">{{ old('emailbody', $customer->emailbody) }}</textarea>
                </div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Änderungen speichern</button>
            </div>
        </form>

        <hr>

    </div>

</x-layout>
