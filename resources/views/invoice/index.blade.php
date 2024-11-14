<x-layout>

        <div class="container">
            <div class="row mb-4">
                <div class = "col text-left">
                    <h3>Rechnungen</h3>
                </div>
                <div class = "col text-right">
                    <a href="{{ route('dashboard') }}" class="btn btn-transparent">Zurück</a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row" id="invoiceList">
                <div class="col-md-4 text-left">
                    <form id="searchForm" class="form-inline">
                        <input type="text" id="searchInput" name="search" class="form-control mr-2" placeholder="Suchen">
                        <button type="submit" class="btn btn-secondary mr-2">Suchen</button>
                    </form>
                </div>

                <div class="col text-right">
                    <button id="newInvoiceButton" class="btn btn-transparent">+ Neu</button>
                    <button id="viewInvoiceButton"  class="btn btn-transparent" disabled>Vorschau</button>
                    <button id="editInvoiceButton"  class="btn btn-transparent" disabled>Bearbeiten</button>
                    <button id="pdfExportButton"  class="btn btn-transparent" disabled>PDF</button>
                    <button id="sendEmailButton"  class="btn btn-transparent" disabled>Senden</button>
                    <button id="archiveButton"  class="btn btn-transparent" disabled>Archiv</button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">Id</th>
                                    <th style="width: 8%;">Nummer</th>
                                    <th style="width: 8%;">Datum</th>
                                    <th style="width: 29.5%;">Kunde</th>
                                    <th style="width: 29.5%;">Beschreibung</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceTable">
                                @forelse($invoices as $invoice)
                                    <tr data-id="{{ $invoice->id }}">
                                        <td>{{ $invoice->id }}</td>
                                        <td>{{ $invoice->number }}</td>
                                        <td>{{ $invoice->date }}</td>
                                        <td class='align-middle'>
                                            {{ $invoice->customername ?? $invoice->companyname ?? 'Kein Kunde' }}
                                        </td>
                                        <td class='align-middle'>{{ $invoice->comment ?? 'Kein Kommentar' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">Keine Kunden gefunden</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
               let selectedInvoiceId = null;

               $('#invoiceTable').on('click', 'tr', function() {
                   $('#invoiceTable tr').removeClass('selected-row');
                   $(this).addClass('selected-row');
                   selectedInvoiceId = $(this).data('id');
                   console.log(selectedInvoiceId);
                   $('#viewInvoiceButton').prop('disabled', false);
                   $('#editInvoiceButton').prop('disabled', false);
                   $('#pdfExportButton').prop('disabled', false);
                   $('#sendEmailButton').prop('disabled', false);
                   $('#createInvoiceButton').prop('disabled', false);
                   $('#archiveButton').prop('disabled', false);
                });



               $('#searchForm').submit(function(event) {
                   event.preventDefault();
                   let searchQuery = $('#searchInput').val();

                   // AJAX request to search for offers
                   $.ajax({
                       url: 'includes/invoice_search.inc.php',
                       type: 'GET',
                       data: { search: searchQuery },
                       success: function(response) {
                           try {
                               // Parse the JSON response from the server
                               let results = JSON.parse(response);
                               let tableContent = '';

                               // Loop through each result and construct the table rows
                               results.forEach(function(row) {
                                   // Function to format a date as dd.mm.yyyy
                                   function formatDate(dateString) {
                                       const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
                                       const date = new Date(dateString);
                                       return date.toLocaleDateString('de-DE', options); // Format date as dd.mm.yyyy
                                   }

                                   // Generate table content with formatted date
                                   tableContent += `
                                       <tr data-id="${row.Id}">
                                           <td class='align-middle'>${row.InvoiceNumber}</td>
                                           <td class='align-middle'>${formatDate(row.InvoiceDate)}</td>
                                           <td class='align-middle'>${row.CustomerName || row.CompanyName}</td>
                                           <td class='align-middle'>${row.Comment}</td>
                                       </tr>`;
                               });

                               // Update the table with the new rows
                               $('#invoiceTable').html(tableContent);

                           } catch (e) {
                               console.error('Failed to parse JSON response', e);
                           }
                       },
                       error: function(xhr, status, error) {
                           console.error('AJAX Error: ', status, error);
                       }
                   });
               });

               $('#newInvoiceButton').click(function() {
                   console.log("Rechnung anlegen Button wurde geklickt");
                   window.location.href = '{{ route('invoice.create') }}';
               });

               $('#viewInvoiceButton').click(function() {
                    console.log("Ansicht Button wurde geklickt");

                    if (selectedInvoiceId) {
                        // Dynamisches Weiterleiten zur GET-Route mit der Rechnungs-ID
                        window.location.href = '{{ route('invoice.show', '') }}/' + selectedInvoiceId;
                    }
                });

               $('#pdfExportButton').click(function() {
                   console.log("Export Button wurde geklickt");

                   if (selectedInvoiceId) {
                        // Dynamisches Weiterleiten zur GET-Route mit der Rechnungs-ID
                        window.location.href = '{{ route('invoice.edit', '') }}/' + selectedInvoiceId;
                    }
               });

               $('#editInvoiceButton').click(function() {
                    console.log("Bearbeiten Button wurde geklickt");

                    if (selectedInvoiceId) {
                        // Dynamisches Weiterleiten zur GET-Route mit der Rechnungs-ID
                        window.location.href = '{{ route('invoice.edit', ['invoice' => '__selectedInvoiceId__']) }}'.replace('__selectedInvoiceId__', selectedInvoiceId);
                    }
                });

               $('#searchInput').keyup(function(){
                   var query = $(this).val().toLowerCase();
                   $('#invoiceList tbody tr').filter(function(){
                       $(this).toggle($(this).text().toLowerCase().indexOf(query) > -1)
                   });
               });

               $('#sendEmailButton').click(function() {
                   console.log("SendEmail Button wurde geklickt");
                   if (selectedInvoiceId) {
                       $('<form>', {
                           'action': 'object_send_over_email.php',
                           'method': 'post',
                           'style': 'display: none;',
                           'html': [
                               $('<input>', { type: 'hidden', name: 'objectid', value: selectedInvoiceId}),
                               $('<input>', { type: 'hidden', name: 'objecttype', value: 'invoice'})
                           ]
                       }).appendTo('body').submit();
                   }
               });

               $('#archiveButton').click(function() {
                   console.log("Invoice archive Button wurde geklickt");
                   if (selectedInvoiceId) {
                       $('<form>', {
                           'action': 'archive_invoice.php',
                           'method': 'post',
                           'style': 'display: none;',
                           'html': [
                               $('<input>', { type: 'hidden', name: 'objectid', value: selectedInvoiceId}),
                               $('<input>', { type: 'hidden', name: 'objecttype', value: 'invoice'})
                           ]
                       }).appendTo('body').submit();
                   }
               });
           });
       </script>
</x-layout>
