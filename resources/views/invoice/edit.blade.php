<x-layout>
    <!-- Alpine.js-Datenkontext für Button und Modal -->
    <div x-data="{ openCustomerModal: false }">

        <div class="container mx-auto px-4 py-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="space-y-4">
                    <div class="border-b border-gray-900/10 pb-4">
                        <h2 class="border-b text-base/8 font-semibold text-gray-900">Rechnung bearbeiten</h2>
                        <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="col-span-5">
                                <h2 class="text-base/7 font-semibold text-gray-900">Kundendaten</h2>
                                <livewire:customer.customer-data :invoiceId="$invoice->id">
                                <!-- Button zum Öffnen des Modals unter den Kundendaten -->
                                <button
                                    @click="openCustomerModal = true"
                                    class="inline-block float-left mt-2 rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Kunden ändern
                                </button>

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

                    <!-- Restlicher Seiteninhalt bleibt unverändert -->
                    <div class="border-b border-gray-900/10 pb-4">
                        <h2 class="text-base/7 font-semibold text-gray-900">Rechnungsdetails</h2>
                        <div>
                            <livewire:invoice.invoicedetails :invoiceId="$invoice->id"/>
                        </div>
                    </div>
                    <div class="border-b border-gray-900/10 pb-4">
                        <h2 class="text-base/7 font-semibold text-gray-900">Zusätzliche Informationen</h2>
                        <livewire:invoice.comment-description :invoiceId="$invoice->id" />
                    </div>
                    <div>
                        <livewire:invoicepositions-table :invoiceId="$invoice->id"/>
                    </div>
                    <div class="mt-2 grid md:grid-cols-5 gap-x-6 gap-y-8 sm:grid-cols-1 border-b border-t border-gray-900/10 pt-2 pb-4">
                        <livewire:invoice.depositamount :invoiceId="$invoice->id"/>
                        <livewire:invoice.calculations :invoiceId="$invoice->id"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kunden-Auswahl Modal -->
        <div x-show="openCustomerModal" @customer-updated.window="openCustomerModal = false"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50" style="display: none;" x-cloak>
                <div class="bg-white rounded-lg shadow-lg  p-6 overflow-y-scroll w-5/6 md:w-4/6 lg:w-3/5"
                    @click.away="openCustomerModal = false">
                <h2 class="text-lg font-semibold mb-4">Kunden auswählen</h2>

                <!-- Inhalt des Popups -->
                <livewire:customer.search-list :invoiceId="$invoice->id" />

                <!-- Schließen-Button -->
                <div class="mt-4 text-right">
                    <button @click="openCustomerModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Schließen
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function handleCustomerUpdated(event) {
            // Schließe das Modal
            openCustomerModal = false;
            // Optional: Aktualisiere den Bereich mit Kundendaten
            // z.B., indem du die Seite neu lädst oder einen Teil neu renderst
            // location.reload();  // falls ein vollständiger Reload gewünscht
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        document.addEventListener('comment-updated', (event) => {
            console.log(event.detail[0].message);
            // Alert entfernt - Erfolgsmeldung wird jetzt nur noch in der Komponente angezeigt
        });
    </script>
</x-layout>
