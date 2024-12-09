<x-layout> 
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Rechnung bearbeiten</h2>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <label class="block text-sm/6 font-medium text-gray-900">Kundendaten</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-900">{{ $invoice->companyname }}</p>
                                <p class="text-sm text-gray-900">{{ $invoice->customername }}</p>
                                <p class="text-sm text-gray-900">{{ $invoice->address }}</p>
                                <p class="text-sm text-gray-900">{{ $invoice->postalcode }} {{ $invoice->location }} </p>
                                <p class="text-sm text-gray-900">{{ $invoice->country }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Rechnungsdetails</h2> 
                    <livewire:invoice.invoicedetails :invoiceId="$invoice->id" />
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Zusätzliche Informationen</h2>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <label for="description" class="block text-sm/6 font-medium text-gray-900">Beschreibung - erscheint nicht in Rechnung</label>
                            <input type="text" id="description" name="description" value="{{ $invoice->description }}" class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                        <div class="col-span-full">
                            <label for="comment" class="block text-sm/6 font-medium text-gray-900">Rechnungskommentar</label>
                            <input type="text" id="comment" name="comment" value="{{ $invoice->comment }}" class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>
                </div>

                <livewire:invoicepositions-table :invoiceId="$invoice->id" />

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Anzahlung</h2>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="depositamount" class="block text-sm/6 font-medium text-gray-900">Anzahlung in Euro</label>
                            <input type="number" step="0.01" id="depositamount" name="depositamount" value="{{ $invoice->depositamount }}" class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Zusammenfassung</h2>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full text-right">
                            <p>Zwischensumme (Netto): {{ number_format($total_price->total_price, 2, ',', '.') }} €</p>
                            <p>Umsatzsteuer ({{ $invoice->taxrate }} %): {{ number_format($total_price->total_price * ($invoice->taxrate / 100), 2, ',', '.') }} €</p>
                            <hr>
                            <p class="font-semibold">Gesamtsumme: {{ number_format($total_price->total_price * (1 + ($invoice->taxrate / 100)), 2, ',', '.') }} €</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Speichern</button>
            </div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</x-layout>

