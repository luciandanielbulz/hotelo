<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="space-y-4">
                <div class="border-b border-gray-900/10 pb-4">
                    <h1 class="border-b text-base/8 font-semibold text-gray-900">Angebot bearbeiten</h1>
                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-5">
                            <h2 class="text-base/7 font-semibold text-gray-900">Kundendaten</h2>
                            <div class="mt-3">
                                <p class="text-sm text-gray-900">{{ $offer->companyname }}</p>
                                <p class="text-sm text-gray-900">{{ $offer->customername }}</p>
                                <p class="text-sm text-gray-900">{{ $offer->address }}</p>
                                <p class="text-sm text-gray-900">{{ $offer->postalcode }} {{ $offer->location }} </p>
                                <p class="text-sm text-gray-900">{{ $offer->country }}</p>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <button
                                onclick="window.open('{{ route('createoffer.pdf', ['offer_id' => $offer->offer_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}', '_blank')"
                                class="inline-block float-right rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Vorschau
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="text-base/7 font-semibold text-gray-900">Angebotsdetails</h2>
                    <livewire:offer.offerdetails :offerId="$offer->id" />


                </div>

                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="text-base/7 font-semibold text-gray-900">Zusätzliche Informationen</h2>
                    <livewire:offer.comment-description :offerId="$offer->id" />
                </div>

                <!-- Hier wird die Livewire-Komponente eingebunden -->
                <livewire:offerpositions-table :offerId="$offer->offer_id" />

                <div class="border-b border-t border-gray-900/10 pt-2 pb-4">
                    <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
                        <div class="sm:col-span-4">
                        </div>
                        <div class="sm:col-span-2 text-right">
                            <p>Zwischensumme (Netto):</p>
                            <p>Umsatzsteuer ({{ $offer->taxrate }} %):</p>
                            <p class="font-semibold border-t border-b-2">Gesamtsumme:</p>
                        </div>

                        <div class="sm:col-span-1 text-left">
                            <p>{{ number_format($total_price->total_price, 2, ',', '.') }} €</p>
                            <p>{{ number_format($total_price->total_price * ($offer->taxrate / 100), 2, ',', '.') }} €</p>
                            <p class="font-semibold border-t border-b-2">{{ number_format($total_price->total_price * (1 + ($offer->taxrate / 100)), 2, ',', '.') }} €</p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <script>
        document.addEventListener('comment-updated', (event) => {
            console.log(event.detail[0].message);
            // Alert entfernt - Erfolgsmeldung wird jetzt nur noch in der Komponente angezeigt
        });
    </script>

</x-layout>
