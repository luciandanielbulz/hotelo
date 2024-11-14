<x-layout>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Rechnung bearbeiten</h3>
            </div>
            <div class="col col-auto d-flex align-items-center">
                <a href="{{ route('invoice.index') }}" class="btn btn-transparent">Zurück</a>
                <form method="post" action="#" class="m-0">
                    <input type="hidden" name="invoiceid" value="">
                    <input type="hidden" name="prev" value="1">
                    <input type="hidden" name="objecttype" value="2">
                    <button type="submit" class="btn btn-transparent">Vorschau</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container p-3">
        <hr>

        <!-----------------------Kunde----------------------->
        <div class="row">
            <div class="col">
                <h5>Kunde</h5>
            </div>

            <div class="col">
                <form method="post" action="#" class="m-0">
                    <input type="hidden" name="invoiceId" value="">
                    <button type="submit" class="btn btn-transparent">Ändern</button>
                </form>
            </div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>

        </div>
        <div class="row">
            <div class="col">
                <label class="label-client">{{ $invoice->companyname }}</label><br>
                <label class="label-client">{{ $invoice->address}}</label><br>
                <label class="label-client">{{ $invoice->country}}</label>
            </div>
        </div>

        <hr>

        <!-- Steuersatz -->
        <div class="row">
            <div class="col">
                <form id="taxrateForm">
                    <label for="taxrateid">Steuersatz</label>
                    <select class="form-control" id="taxrateid" name="taxrateid">
                        <option value="1" {{ $invoice->tax_id == 1 ? 'selected' : '' }}>0 %</option>
                        <option value="2" {{ $invoice->tax_id == 2 ? 'selected' : '' }}>20 %</option>
                    </select>
                </form>
            </div>
            <!-- Rechnungsdatum -->
            <div class="col">
                <form id="dateForm">
                    <label for="invoiceDate">Rechnungsdatum</label>
                    <input type="date" class="form-control" id="invoiceDate" name="invoiceDate" value="{{$invoice->date}}">
                </form>
            </div>
            <!-- Rechnungsnummer -->
            <div class="col">
                <form id="numberForm">
                    <label for="invoiceNumber">Rechnungsnummer</label>
                    <input class="form-control" type="text" id="invoiceNumber" name="invoiceNumber" value="{{$invoice->number}}">
                </form>
            </div>

            <!-- Konditionen-->
            <div class="col">
                <form id="conditionForm">
                    <label for="condition_id">Zahlungsziel</label>
                    <select class="form-control" id="condition_id" name="condition_id">
                        @foreach ($conditions as $condition)
                            <!-- Setze den Wert der Option auf die ID der Bedingung -->
                            <option value="{{ $condition->id }}" {{ $invoice->condition_id == $condition->id ? 'selected' : '' }}>
                                {{ $condition->conditionname }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        <hr>

        <!-- Beschreibung -->
        <div class="row">
            <div class="col">
                <form id="descriptionForm">
                    <label for="description">Beschreibung - erscheint nicht in Rechnung</label>
                    <input type="text" class="form-control" name="description" id="description" value = "{{$invoice->description}}">
                </form>
            </div>
        </div>

        <hr>
        <!-- Rechnungkommentar -->
        <div class="row">
            <div class="col">
                <form id="commentForm">
                    <label for="comment">Rechnungskommentar</label>
                    <input type="text" class="form-control" id="comment" name="comment" value="{{$invoice->comment}}">
                </form>
            </div>
        </div>

        <!-- Beschreibung Ende -->

        <hr>
        <livewire:invoicepositions-table :invoiceId="$invoice->id" />
    </div>


        <hr>
        <div class="row">
            <div class="col">
                <form id="depositForm">
                    <label for="depositamount">Anzahlung in Euro</label>
                    <input class="form-control" type="number" step="0.01" id="depositamount" name="depositamount" value="{{$invoice->depositamount}}">
                </form>
            </div>
            <div class="col-xs-6 col-md-4 text-right">
                Zwischensumme (Netto):<br>
                Umsatzsteuer (%):<br><hr>
                <b>Gesamtsumme:</b>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="col-xs-6 col-md-4">
                    {{ number_format($total_price->total_price, 2, ',', '.') }} €<br>
                    {{ number_format($total_price->total_price * (20 / 100), 2, ',', '.') }}  €<br><hr>
                    {{ number_format($total_price->total_price*1.2, 2, ',', '.') }} €
                </div>
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
            let selectedPositionId = null;

            $('#positionsTable').on('click', 'tr', function() {
                $('#positionTable tr').removeClass('selected-row');
                $(this).addClass('selected-row');
                selectedPositionId = $(this).data('id');
                console.log(selectedPositionId);
                $('#editPosition').prop('disabled', false);
                $('#deletePosition').prop('disabled', false);

            });

            $('#viewInvoiceButton').click(function() {
            const url = '{{ route("create.pdf") }}' +
                '?invoice_id=' + {{$invoice->invoiceid}} +
                '&objecttype=invoice' +
                '&prev=1';
            window.open(url, '_blank');
            });

            $('#taxrateid').change(function() {
                var invoiceId = "{{ $invoice->id }}";  // Holt das Angebots-ID
                var taxrateid = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(taxrateid);
                console.log(invoiceId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("invoice.updatetaxrate") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        invoice_id: invoiceId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        tax_id: taxrateid,
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response.message);
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            $('#invoiceDate').change(function() {
                var invoiceId = "{{ $invoice->id }}";  // Holt das Angebots-ID
                var invoiceDate = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(invoiceDate);
                console.log(invoiceId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("invoice.updateinvoicedate") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        invoice_id: invoiceId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        invoicedate: invoiceDate,  // Steuersatz-ID (wird aus der Anwendung übergeben)
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

            $('#invoiceNumber').change(function() {
                var invoiceId = "{{ $invoice->id }}";
                var number = $(this).val();
                $.ajax({
                    url: '{{ route("invoice.updatenumber") }}',
                    type: 'POST',
                    data: {
                        invoice_id: invoiceId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        number: number,  // Steuersatz-ID (wird aus der Anwendung übergeben)
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response);
                        alert(response.message);
                    },

                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            $('#condition_id').change(function() {
                var invoiceId = "{{ $invoice->id }}";
                var conditionId = $(this).val();
                console.log($(this));
                $.ajax({
                    url: '{{ route("invoice.updatecondition") }}',
                    type: 'POST',
                    data: {
                        invoice_id: invoiceId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        condition_id: conditionId,  // Steuersatz-ID (wird aus der Anwendung übergeben)
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response);
                        alert(response.message);
                    },

                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            $('#description').change(function() {
                var invoiceId = "{{ $invoice->id }}";  // Holt das Angebots-ID
                var description = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(description);
                console.log(invoiceId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("invoice.updatedescription") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        invoice_id: invoiceId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        description: description,  // Steuersatz-ID (wird aus der Anwendung übergeben)
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

            $('#comment').change(function() {
                var invoiceId = "{{ $invoice->id }}";  // Holt das Angebots-ID
                var comment = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(comment);
                console.log(invoiceId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("invoice.updatecomment") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        invoice_id: invoiceId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        comment: comment,  // Steuersatz-ID (wird aus der Anwendung übergeben)
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response.message);
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.log('Fehler beim Aktualisieren des Steuersatzes');
                        alert(error);
                    }
                });
            });

            $('#depositamount').change(function() {
                var invoiceId = "{{ $invoice->id }}";  // Holt das Angebots-ID
                var depositamount = $(this).val();  // Holt den ausgewählten Steuersatz
                console.log(depositamount);
                console.log(invoiceId);
                // AJAX Anfrage an den Server senden
                $.ajax({
                    url: '{{ route("invoice.updatedeposit") }}',  // Die Route, die die Aktualisierung entgegennimmt
                    method: 'POST',
                    data: {
                        invoice_id: invoiceId,  // Anbiet-ID (wird aus der Anwendung übergeben)
                        depositamount: depositamount,  // Steuersatz-ID (wird aus der Anwendung übergeben)
                        _token: '{{ csrf_token() }}'  // CSRF-Token
                    },

                    success: function(response) {
                        console.log(response.message);
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.log('Fehler beim Aktualisieren des Steuersatzes');
                        showPopup();
                    }
                });
            });


            // Position bearbeiten
            $('#editPosition').click(function() {
                console.log("bearbeiten geklickt");

                if (!selectedPositionId) {
                    console.log("Keine Position ausgewählt");
                    return; // Abbruch, wenn keine ID gesetzt ist
                }

                // Weiterleitung zur neuen Seite
                window.location.href = `/invoiceposition/${selectedPositionId}/edit`;
            });

            // Position löschen
            $('#deletePosition').click(function() {
                let invoiceid = Null; <?php ?> //echo json_encode($invoiceId); ?>;
                if (selectedPositionId) {
                    if (confirm('Möchten Sie diese Position wirklich löschen?')) {
                        $.ajax({
                            url: 'includes/invoice_position_delete.php',
                            type: 'POST',
                            data: { id: selectedPositionId, invoiceid: invoiceid },
                            success: function(response) {
                                console.log(response);
                                location.reload(); // Seite neu laden nach dem Löschen
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                                alert('Fehler beim Löschen der Position.');
                            }
                        });
                    }
                }
            });


            // Zahlungsziel ändern via AJAX
            $('#paymenttermid').on('change', function() {
                var formData = $('#paymentForm').serialize();

                $.ajax({
                    url: 'includes/invoice_paymentterm_update.inc.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        showPopup(); // Zeigt eine Bestätigungsmeldung
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Fehler beim Aktualisieren des Zahlungsziels.');
                    }
                });
            });

            // Zeigt ein Popup zur Bestätigung
            function showPopup() {
                var popup = document.getElementById('popup');
                if (!popup) {
                    console.error('Element mit ID "popup" nicht gefunden.');
                    return;
                }
                popup.classList.remove('hide');
                popup.classList.add('show');
                setTimeout(function() {
                    popup.classList.remove('show');
                    popup.classList.add('hide');
                }, 3000); // Popup wird nach 3 Sekunden ausgeblendet
            }
        });

    </script>

</x-layout>
