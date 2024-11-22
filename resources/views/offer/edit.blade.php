<x-layout>

    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Angebot bearbeiten - {{$offercontent->offer_id}}</h3>
            </div>
            <div class="col col-auto d-flex align-items-center">
                <a href="{{ route('offer.index') }}" class="btn btn-transparent me-2">Zurück</a>
                <button id="viewOfferButton" class="btn btn-transparent">Vorschau</button>
            </div>
        </div>
    </div>


    <div class="container">
        <hr>

        <!-----------------------Kunde----------------------->
        <div class="row mt-2 mb-3">
            <div class="col">
                <h5>Kunde</h5>
            </div>
            <div class="col">
                <form method="post" action="#" class="m-0">
                    <input type="hidden" name="offerId" value="">
                    <button type="submit" class="btn btn-transparent">Ändern</button>
                </form>
            </div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>

        </div>
        <div class = "row">
            <div class="col">
                <label class="label-client">{{ $offercontent->companyname }}</label><br>
                <label class="label-client">{{ $offercontent->customername }}</label><br>
                <label class="label-client">{{ $offercontent->address}}</label><br>
                <label class="label-client">{{ $offercontent->country}}</label>
            </div>
        </div>

        <hr>

        <!-----------------------Steuersatz & Angebotsdatum----------------------->
        <div class = "row mt-2 mb-3">
            <div class="col">
                <form id="taxrateForm">
                    <label for="taxrateid">Steuersatz</label>
                    <select class="form-control" id="taxrateid" name="taxrateid">
                        <option value="1" {{ $offercontent->tax_id == 1 ? 'selected' : '' }}>0 %</option>
                        <option value="2" {{ $offercontent->tax_id == 2 ? 'selected' : '' }}>20 %</option>
                    </select>
                </form>
            </div>

            <!---------------------------------------------->
            <div class="col">
                <form id="offerDateForm">
                    <label for="offerDate">Datum</label>
                    <input type="date" class="form-control" id="offerDate" name ="offerDate" value="{{ $offercontent->date ? $offercontent->date->format('Y-m-d') : '' }}">
                </form>
            </div>

            <div class="col">
                <form id="offerNumberForm">
                    <label for="offerNumber">Nummer</label>
                    <input type="input" class="form-control" id = "offerNumber" name ="offerNumber" value="{{ $offercontent->number }}">
                </form>
            </div>
        </div>
        <hr>

        <!-----------------------Beschreibung----------------------->
        <div class="row mt-2 mb-3">
            <div class="col">
                <form id="commentForm">
                    <label for="description">Beschreibung - erscheint nicht in Rechnung</label>
                    <input class="form-control" name="description" id="description" value = "{{$offercontent->description}}"></input>
                </form>
            </div>

        </div>

        <hr>
        <!-----------------------Angebotskommentar----------------------->
        <div class="row mt-2 mb-3">
            <div class = "col">
                <form id="descriptionForm">
                    <label for="comment">Angebotskommentar</label>
                    <input type="text" class="form-control" id="comment" name="comment" value="{{$offercontent->comment}}"></input>
                </form>
            </div>
        </div>

        <!-----------------------Beschreibung Ende----------------------->

        <hr>

        <livewire:offerpositions-table :offerId="$offercontent->offer_id" />
    </div>


    <hr>
    <div class ="row mt-2 mb-3">
        <div class="col"></div>


        <div class="col"></div>

        <div class="col"></div>
        <div class="col">
            Zwischensumme (Netto):<br>
            Umsatzsteuer ({{$offercontent->taxrate}} %):<br><hr>
            <b>Gesamtsumme:</b>
        </div>

        <div class="col">

            {{ number_format($total_price->total_price, 2, ',', '.') }} €<br>
            {{ number_format($total_price->total_price * ($offercontent->taxrate / 100), 2, ',', '.') }}  €<br><hr>
            {{ number_format($total_price->total_price*($offercontent->taxrate / 100+1), 2, ',', '.') }} €

        </div>



    </div>

</div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#positionsTable').on('click', 'tr', function() {
                $('#positionsTable tr').removeClass('selected-row');
                $(this).addClass('selected-row');
                selectedPositionId = $(this).data('id');
                console.log(selectedPositionId);
                $('#editPosition').prop('disabled', false);
                $('#deletePosition').prop('disabled', false);
            });

            $('#viewOfferButton').click(function() {
                const url = '{{ route("createoffer.pdf") }}' +
                    '?offer_id=' + {{$offercontent->offer_id}} +
                    '&objecttype=offer' +
                    '&prev=I';
                window.open(url, '_blank');
            });

            $('#taxrateid').change(function() {
                var offerId = "{{ $offercontent->offer_id }}";  // Holt das Angebots-ID
                var taxrateid = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(taxrateid);
                console.log(offerId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("offer.updatetaxrate") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        offer_id: offerId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        tax_id: taxrateid,  // Steuersatz-ID (wird aus der Anwendung übergeben)
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response.message);
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.log('Fehler beim Aktualisieren des Steuersatzes');
                    }
                });
            });

            $('#offerNumber').change(function() {
                var offerId = "{{ $offercontent->offer_id }}";  // Holt das Angebots-ID
                var offernumber = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(offernumber);
                console.log(offerId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("offer.updatenumber") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        offer_id: offerId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        number: offernumber,  // Steuersatz-ID (wird aus der Anwendung übergeben)
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response.message);
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.log('Fehler beim Aktualisieren des Steuersatzes');
                        alert('Fehler beim Aktualisieren der Angebotsnummer, bitte Administrator kontaktieren!');
                    }
                });
            });

            $('#editPosition').click(function() {
                console.log("bearbeiten geklickt");

                if (!selectedPositionId) {
                    console.log("Keine Position ausgewählt");
                    return; // Abbruch, wenn keine ID gesetzt ist
                }

                // Weiterleitung zur neuen Seite
                window.location.href = `/offerposition/${selectedPositionId}/edit`;
            });


            $('#offerDate').change(function() {
                var offerId = "{{ $offercontent->offer_id }}";  // Holt das Angebots-ID
                var offerDate = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(offerDate);
                console.log(offerId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("offer.updateofferdate") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        offer_id: offerId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        offerdate: offerDate,  // Steuersatz-ID (wird aus der Anwendung übergeben)
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response.message);
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.log('Fehler beim Aktualisieren des Steuersatzes');
                        alert('Fehler beim Aktualisieren des Datums, bitte Administrator kontaktieren!');
                    }
                });
            });

            $('#description').change(function() {
                var offerId = "{{ $offercontent->offer_id }}";  // Holt das Angebots-ID
                var description = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(description);
                console.log(offerId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("offer.updatedescription") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        offer_id: offerId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        description: description,  // Steuersatz-ID (wird aus der Anwendung übergeben)
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response.message);
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.log('Fehler beim Aktualisieren des Steuersatzes');
                        alert('Fehler beim Aktualisieren der Beschreibung, bitte Administrator kontaktieren!');
                    }
                });
            });

            $('#comment').change(function() {
                var offerId = "{{ $offercontent->offer_id }}";  // Holt das Angebots-ID
                var comment = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(comment);
                console.log(offerId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("offer.updatecomment") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        offer_id: offerId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        comment: comment,  // Steuersatz-ID (wird aus der Anwendung übergeben)
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response.message);
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.log('Fehler beim Aktualisieren des Steuersatzes');
                        alert('Fehler beim Aktualisieren des Kommentars, bitte Administrator kontaktieren!');
                    }
                });
            });
        });
    </script>
</x-layout>
