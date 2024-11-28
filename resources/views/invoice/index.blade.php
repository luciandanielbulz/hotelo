<x-layout>

        <div class="container">
            <div class="row">
                <div class="col-md-4 text-left">
                    <form id="searchForm" class="form-inline" method="GET" action="{{ route('invoice.index') }}">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Suchen" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-secondary">Suchen</button>
                    </form>
                </div>

                <div class="col text-right">
                    <button class="btn btn-transparent" onclick="window.location.href='{{ route('customer.index') }}'">+ Neu</button>
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
                                    <th style="width: 20.0%;">Beschreibung</th>
                                    <th style="width: 9.5%;">Betrag</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceTable">
                                @forelse($invoices as $invoice)
                                    <tr data-id="{{ $invoice->id }}">
                                        <td>{{ $invoice->id }}</td>
                                        <td>{{ $invoice->number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->date)->translatedFormat('d.m.Y') }}</td>
                                        <td class='align-middle'>
                                            {{ $invoice->customername ?? $invoice->companyname ?? 'Kein Kunde' }}
                                        </td>
                                        <td class='align-middle'>{{ $invoice->comment ?? 'Kein Kommentar' }}</td>
                                        <td class='align-middle'>{{ number_format($invoice->total_price, 2, ',', '.') }} €</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">Keine Datensätze gefunden</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div>
                            {{ $invoices->links() }}
                        </div>
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

                $('#viewInvoiceButton').click(function() {
                    const url = '{{ route("createinvoice.pdf") }}' +
                        '?invoice_id=' + selectedInvoiceId +
                        '&objecttype=invoice' +
                        '&prev=I';
                    window.open(url, '_blank'); // PDF im neuen Tab öffnen
                });

                $('#pdfExportButton').click(function() {
                    const url = '{{ route("createinvoice.pdf") }}' +
                        '?invoice_id=' + selectedInvoiceId +
                        '&objecttype=invoice' +
                        '&prev=D';
                    window.open(url, '_blank'); // PDF im neuen Tab öffnen
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

                $('#sendEmailButton').click(function () {
                    console.log("SendEmail Button wurde geklickt");

                    if (selectedInvoiceId) {
                        $('<form>', {
                            'action': '{{ route('invoice.sendmail') }}',
                            'method': 'post',
                            'style': 'display: none;',
                        }).append(
                            $('<input>', {
                                type: 'hidden',
                                name: 'objectid',
                                value: selectedInvoiceId
                            }),
                            $('<input>', {
                                type: 'hidden',
                                name: 'objecttype',
                                value: 'invoice'
                            }),
                            $('<input>', {
                                type: 'hidden',
                                name: '_token',
                                value: '{{ csrf_token() }}'
                            })
                        ).appendTo('body').submit();
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
