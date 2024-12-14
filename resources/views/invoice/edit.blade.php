<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="space-y-4">
                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="border-b text-base/8 font-semibold text-gray-900">Rechnung bearbeiten</h2>
                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-5">
                            <h2 class="text-base/7 font-semibold text-gray-900">Kundendaten</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-900">{{ $invoice->companyname }}</p>
                                <p class="text-sm text-gray-900">{{ $invoice->customername }}</p>
                                <p class="text-sm text-gray-900">{{ $invoice->address }}</p>
                                <p class="text-sm text-gray-900">{{ $invoice->postalcode }} {{ $invoice->location }} </p>
                                <p class="text-sm text-gray-900">{{ $invoice->country }}</p>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <button
                                onclick="window.open('{{ route('createinvoice.pdf', ['invoice_id' => $invoice->invoice_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}', '_blank')"
                                class="inline-block float-right rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Vorschau
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="text-base/7 font-semibold text-gray-900">Rechnungsdetails</h2>
                    <div>
                        <livewire:invoice.invoicedetails :invoiceId="$invoice->id" />
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="text-base/7 font-semibold text-gray-900">Zusätzliche Informationen</h2>
                    <livewire:invoice.comment-description :invoiceId="$invoice->id" />
                </div>
                <div>
                    <livewire:invoicepositions-table :invoiceId="$invoice->id" />
                </div>
                <div>
                    <div class="border-b border-t border-gray-900/10 pt-2 pb-4">

                        <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
                            <!-- Formular für Anzahlung -->
                            <div class="sm:col-span-2 md:col-span-2">

                            </div>

                            <!-- Leere Spalte für Abstand -->
                            <div class="sm:col-span-2"></div>

                            <!-- Zusammenfassung der Beträge -->
                            <div class="sm:col-span-2 text-right">
                                <p>Zwischensumme (Netto):</p>
                                <p>Umsatzsteuer ({{ $invoice->taxrate }} %):</p>
                                <p class="font-semibold border-t border-b">Gesamtsumme:</p>

                                @if ($invoice->depositamount > 0)
                                    <p class="border-t border-b">Anzahlung:</p>
                                    <p class="font-semibold border-t border-b-2">Zu Zahlen:</p>
                                @endif
                            </div>

                            <!-- Werte für Zusammenfassung -->
                            <div class="sm:col-span-2 text-left">
                                <p>{{ number_format($total_price->total_price, 2, ',', '.') }} €</p>
                                <p>{{ number_format($total_price->total_price * ($invoice->taxrate / 100), 2, ',', '.') }} €</p>
                                <p class="font-semibold border-t border-b">{{ number_format($total_price->total_price * (1 + ($invoice->taxrate / 100)), 2, ',', '.') }} €</p>

                                @if ($invoice->depositamount > 0)
                                    <p class="border-t border-b">{{ number_format($invoice->depositamount * -1, 2, ',', '.') }} €</p>
                                    <p class="font-semibold border-t border-b-2">{{ number_format($total_price->total_price * (1 + ($invoice->taxrate / 100)) - $invoice->depositamount, 2, ',', '.') }} €</p>
                                @endif
                            </div>
                        </div>
                    </div>

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

        document.addEventListener('comment-updated', (event) => {
            console.log(event.detail[0].message);
            alert(event.detail[0].message);
        });
     </script>
</x-layout>

