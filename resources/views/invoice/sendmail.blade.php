<x-layout>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Rechnung per E-Mail versenden</h3>
            </div>
            <div class="col col-auto d-flex align-items-center">
                <a href="{{ route('invoice.index') }}" class="btn btn-transparent">Zurück</a>
                <button id="sendInvoiceButton" class="btn btn-transparent">Vorschau</button>
            </div>
        </div>
    </div>
    <div class="container">


        <div class="row">
            <div class="col">
                <form method="post" id="myForm">
                    <div class="form-group">
                        <label for="email">E-Mail Adresse:</label>
                        <input class="form-control" type="email" id="email" name="email" value="{{ $clientdata->email }}" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Betreff:</label>
                        <input class="form-control" type="text" id="subject" name="subject" value="{{ $emailsubject }}" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Nachricht:</label>
                        <input type="hidden" id="invoiceId" value="{{ $clientdata->invoice_id }}">
                        <textarea class="form-control summernote" id="message" name="message" rows="7">{{ $emailbody }}</textarea>
                    </div>

                    <div class="form-group text-center">
                        <button type="button" id="sendEmailButton" class="btn btn-primary">Absenden</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Spinner für Ladeanzeige -->
    <div id="loadingSpinner" style="display:none; text-align: center;">
        <div class="spinner-border" role="status">
            <span class="sr-only">Laden...</span>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Summernote-Editor initialisieren
            $('.summernote').summernote({
                height: 300
            });

            // Klick-Event für den Button
            $('#sendEmailButton').click(function(e) {
                e.preventDefault(); // Verhindert das Standardverhalten des Buttons

                // Daten aus dem Formular abrufen
                const invoiceId = $('#invoiceId').val();
                const email = $('#email').val();
                const subject = $('#subject').val();
                const message = $('#message').val();

                // Lade-Spinner anzeigen
                showSpinner();

                // AJAX-Request
                $.ajax({
                    url: '{{ route("sendinvoice.email") }}',
                    type: 'POST',
                    data: {
                        invoice_id: invoiceId,
                        email: email,
                        subject: subject,
                        message: message,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message || 'Rechnung wurde erfolgreich versendet.');
                        window.location.href = '{{ route("outgoingemails.index") }}';
                    },
                    error: function(xhr) {
                        console.log('Fehler: ' + xhr.responseText);
                    },
                    complete: function() {
                        // Lade-Spinner ausblenden
                        hideSpinner();
                    }
                });
            });

            function showSpinner() {
                $('#loadingSpinner').show();
            }

            function hideSpinner() {
                $('#loadingSpinner').hide();
            }
        });
    </script>
</x-layout>
