<x-layout>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="space-y-6">
                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="text-base/7 font-semibold text-gray-900">Angebot bearbeiten</h2>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <label class="block text-sm/6 font-medium text-gray-900">Kundendaten</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-900">{{ $offer->companyname }}</p>
                                <p class="text-sm text-gray-900">{{ $offer->customername }}</p>
                                <p class="text-sm text-gray-900">{{ $offer->address }}</p>
                                <p class="text-sm text-gray-900">{{ $offer->postalcode }} {{ $offer->location }} </p>
                                <p class="text-sm text-gray-900">{{ $offer->country }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="text-base/7 font-semibold text-gray-900">Rechnungsdetails</h2>
                    <livewire:offer.offerdetails :offerId="$offer->id" />


                </div>

                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="text-base/7 font-semibold text-gray-900">Zusätzliche Informationen</h2>
                    <livewire:offer.comment-description :offerId="$offer->id" />
                </div>

                <!-- Hier wird die Livewire-Komponente eingebunden -->
                <livewire:offerpositions-table :offerId="$offer->offer_id" />

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Zusammenfassung</h2>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full text-right">
                            <p>Zwischensumme (Netto): {{ number_format($total_price->total_price, 2, ',', '.') }} €</p>
                            <p>Umsatzsteuer ({{ $offer->taxrate }} %): {{ number_format($total_price->total_price * ($offer->taxrate / 100), 2, ',', '.') }} €</p>
                            <hr>
                            <p class="font-semibold">Gesamtsumme: {{ number_format($total_price->total_price * (1 + ($offer->taxrate / 100)), 2, ',', '.') }} €</p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <script>
        document.addEventListener('comment-updated', (event) => {
            console.log(event.detail[0].message);
            alert(event.detail[0].message);
        });
    </script>

</x-layout>
