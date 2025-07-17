<x-layout>
    <!-- Moderner Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Angebot bearbeiten</h1>
            <p class="text-gray-600">Angebot #{{ $offer->number }} vom {{ \Carbon\Carbon::parse($offer->date)->translatedFormat('d.m.Y') }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('offer.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/70 backdrop-blur-sm text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                </svg>
                Zurück zur Übersicht
            </a>
            <button onclick="window.open('{{ route('createoffer.pdf', ['offer_id' => $offer->offer_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}', '_blank')"
                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Vorschau
            </button>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Kundendaten Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Kundendaten
                </h2>
            </div>
            
            <livewire:offer.customer-data :offerId="$offer->offer_id" />
        </div>

        <!-- Angebotsdetails Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Angebotsdetails
            </h2>
            <livewire:offer.offerdetails :offerId="$offer->id" />
        </div>

        <!-- Zusätzliche Informationen Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Zusätzliche Informationen
            </h2>
            <livewire:offer.comment-description :offerId="$offer->id" />
        </div>

        <!-- Positionen Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Positionen
            </h2>
            <livewire:offerpositions-table :offerId="$offer->offer_id" />
        </div>

        <!-- Gesamtsumme Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
                Zusammenfassung
            </h2>
            
            <div class="flex justify-end">
                <div class="w-full md:w-1/2 lg:w-1/2 xl:w-1/2 bg-gradient-to-r from-gray-50 to-slate-50 rounded-lg p-4 border border-gray-200 shadow-sm">
                    <!-- Header -->
                    <div class="flex items-center mb-3">
                        <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <h2 class="text-sm font-medium text-gray-700">Berechnungen</h2>
                    </div>
                    
                    <div class="space-y-3">
                        <!-- Zwischensumme -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-200">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-700">Zwischensumme (Netto)</span>
                                </div>
                                <span class="text-base font-semibold text-blue-900">{{ number_format($total_price->total_price, 2, ',', '.') }} €</span>
                            </div>
                        </div>

                        <!-- Umsatzsteuer -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-3 border border-green-200">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    <span class="text-sm font-medium text-green-700">zzgl. Umsatzsteuer ({{ $offer->taxrate }} %)</span>
                                </div>
                                <span class="text-base font-semibold text-green-900">{{ number_format($total_price->total_price * ($offer->taxrate / 100), 2, ',', '.') }} €</span>
                            </div>
                        </div>

                        <!-- Gesamtsumme -->
                        <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-lg p-4 border-2 border-purple-300 shadow-md">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-base font-bold text-purple-700">Gesamtsumme</span>
                                </div>
                                <span class="text-lg font-bold text-purple-900">{{ number_format($total_price->total_price * (1 + ($offer->taxrate / 100)), 2, ',', '.') }} €</span>
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
