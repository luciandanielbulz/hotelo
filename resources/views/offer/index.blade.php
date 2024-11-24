<x-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-left">
                <form id="searchForm" class="form-inline" method="GET" action="{{ route('offer.index') }}">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Suchen" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-secondary">Suchen</button>
                </form>
            </div>

            <div class="col text-right">
                <button class="btn btn-transparent" onclick="window.location.href='{{ route('customer.index') }}'">+ Neu</button>
                <button id="viewOfferButton" class="btn btn-transparent" disabled>Vorschau</button>
                <button id="editOfferButton" class="btn btn-transparent" disabled>Bearbeiten</button>
                <button id="createInvoiceButton" class="btn btn-transparent" disabled>Rechnung</button>
                <button id="pdfExportButton" class="btn btn-transparent" disabled>PDF</button>
                <button id="sendEmailButton" class="btn btn-transparent" disabled>Senden</button>
                <button id="archiveButton" class="btn btn-transparent" disabled>Archiv</button>

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
                        <tbody id="offerTable">
                            @forelse($offers as $offer)
                                <tr data-id="{{ $offer->offer_id }}">
                                    <td>{{ $offer->offer_id }}</td>
                                    <td>{{ $offer->number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($offer->date)->translatedFormat('d.m.Y') }}</td>
                                    <td class='align-middle'>
                                        {{ $offer->customername ?? $offer->companyname ?? 'Kein Kunde' }}
                                    </td>
                                    <td class='align-middle'>{{ $offer->comment ?? 'Kein Kommentar' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">Keine Kunden gefunden</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div>
                        {{ $offers->links() }}
                    </div>
                </div>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            let selectedOfferId = null;

            $('#offerTable').on('click', 'tr', function() {
                $('#offerTable tr').removeClass('selected-row');
                $(this).addClass('selected-row');
                selectedOfferId = $(this).data('id');
                console.log("Selected Offer ID: " + selectedOfferId);
                $('#viewOfferButton, #editOfferButton, #createInvoiceButton, #pdfExportButton, #sendEmailButton, #archiveButton').prop('disabled', false);
            });

            function createHiddenForm(action, inputs) {
                const form = $('<form>', { action: action, method: 'post', style: 'display: none;' });
                inputs.forEach(input => {
                    form.append($('<input>', input));
                });
                form.appendTo('body').submit();
            }

            $('#viewOfferButton').click(function() {
                const url = '{{ route("createoffer.pdf") }}' +
                    '?offer_id=' + selectedOfferId +
                    '&objecttype=invoice' +
                    '&prev=I';
                window.open(url, '_blank'); // PDF im neuen Tab öffnen
            });

            $('#editOfferButton').click(function() {
                if (selectedOfferId) {
                    // Erstelle ein unsichtbares Formular, um die POST-Anfrage zu senden
                    createHiddenForm('{{ route('offer.edit', ':id') }}'.replace(':id', selectedOfferId), [
                        { type: 'hidden', name: '_method', value: 'GET' }, // Laravel erwartet PUT für Updates
                        { type: 'hidden', name: 'offerid', value: selectedOfferId }
                        // Füge hier weitere Eingabewerte hinzu, die du senden möchtest
                    ]);
                }
            });


            $('#createInvoiceButton').click(function() {
                if (selectedOfferId) {
                    // Dynamische URL mit dem selectedOfferId als Query-Parameter
                    const url = '{{ route('invoice.createinvoicefromoffer') }}' + '?offerid=' + selectedOfferId;
                    // Weiterleitung zur URL
                    window.location.href = url;
                }
            });


            $('#pdfExportButton').click(function() {
                const url = '{{ route("createoffer.pdf") }}' +
                    '?offer_id=' + selectedOfferId +
                    '&objecttype=invoice' +
                    '&prev=D';
                window.open(url, '_blank'); // PDF im neuen Tab öffnen
            });

            $('#sendEmailButton').click(function() {
                if (selectedOfferId) {
                    createHiddenForm('', [
                        { type: 'hidden', name: 'objectid', value: selectedOfferId },
                        { type: 'hidden', name: 'objecttype', value: 'offer' }
                    ]);
                }
            });

            $('#archiveButton').click(function() {
                if (selectedOfferId) {
                    createHiddenForm('', [
                        { type: 'hidden', name: 'offerid', value: selectedOfferId }
                    ]);
                }
            });
        });
    </script>

</x-layout>
