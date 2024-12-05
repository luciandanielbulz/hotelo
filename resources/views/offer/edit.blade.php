<x-layout>
    <div class="container mx-auto px-4 py-8">
        <form class="bg-white p-6 rounded-lg shadow-md">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Angebot bearbeiten</h2>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <label class="block text-sm/6 font-medium text-gray-900">Kundendaten</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-900">{{ $offer->companyname }}</p>
                                <p class="text-sm text-gray-900">{{ $offer->customername }}</p>
                                <p class="text-sm text-gray-900">{{ $offer->address }}</p>
                                <p class="text-sm text-gray-900">{{ $offer->country }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Rechnungsdetails</h2>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="taxrateid" class="block text-sm font-medium text-gray-900">Steuersatz</label>
                            <div class="mt-2">
                                <select id="taxrateid" name="taxrateid" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                                    <option value="1" {{ $offer->tax_id == 1 ? 'selected' : '' }}>0 %</option>
                                    <option value="2" {{ $offer->tax_id == 2 ? 'selected' : '' }}>20 %</option>
                                </select>
                            </div>
                        </div>
                        <!-- Datum -->
                        <div class="sm:col-span-2">
                            <label for="offerDate" class="block text-sm font-medium text-gray-900">Datum</label>
                            <div class="mt-2">
                                <input type="date" id="offerDate" name="offerDate" value="{{ $offer->date ? $offer->date->format('Y-m-d') : '' }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                            </div>
                        </div>
                        <!-- Nummer -->
                        <div class="sm:col-span-2">
                            <label for="offerNumber" class="block text-sm font-medium text-gray-900">Nummer</label>
                            <div class="mt-2">
                                <input type="text" id="offerNumber" name="offerNumber" value="{{ $offer->number }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Zusätzliche Informationen</h2>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <label for="description" class="block text-sm font-medium text-gray-900">Beschreibung - erscheint nicht in Rechnung</label>
                            <input type="text" id="description" name="description" value="{{ $offer->description }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                        </div>
                        <div class="col-span-full">
                            <label for="comment" class="block text-sm font-medium text-gray-900">Angebotskommentar</label>
                            <input type="text" id="comment" name="comment" value="{{ $offer->comment }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                        </div>
                    </div>
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
        </form>
    </div>

    <!-- Vorschau-Skript -->
        <script>
            document.getElementById('viewOfferButton').addEventListener('click', function() {
                const url = '{{ route('createoffer.pdf') }}?offer_id={{ $offer->offer_id }}&objecttype=offer&prev=I';
                window.open(url, '_blank');
            });
        </script>

        <!-- Livewire Scripts -->
        @livewireScripts
</x-layout>
