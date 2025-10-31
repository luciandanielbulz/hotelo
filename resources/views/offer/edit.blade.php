<x-layout>
    <!-- Optimierter Header -->
    <div class="mb-6">
        <!-- Mobile Header - zentriert -->
        <div class="md:hidden text-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Angebot bearbeiten</h1>
            <p class="text-gray-600 mt-1">Angebot #{{ $offerWithDetails->number }} vom {{ \Carbon\Carbon::parse($offerWithDetails->date)->translatedFormat('d.m.Y') }}</p>
        </div>
        
        <!-- Desktop Header - mit Buttons -->
        <div class="hidden md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Angebot bearbeiten</h1>
                <p class="text-gray-600">Angebot #{{ $offerWithDetails->number }} vom {{ \Carbon\Carbon::parse($offerWithDetails->date)->translatedFormat('d.m.Y') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('offer.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/70 backdrop-blur-sm text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                    </svg>
                    Zurück zur Übersicht
                </a>
                <button onclick="window.open('{{ route('createoffer.pdf', ['offer_id' => $offerWithDetails->offer_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}', '_blank')"
                        class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Vorschau
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Kundendaten + Beschreibung (2 Spalten wie Rechnung) -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Kundendaten (links 1/2) -->
                <div x-data="{ openCustomerModal: false }">
            <!-- Mobile Header -->
            <div class="md:hidden mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Kundendaten
                </h2>
                <div class="mt-3 text-center">
                    <button @click="openCustomerModal = true"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Kunden ändern
                    </button>
                </div>
            </div>
            
            <!-- Desktop Header -->
            <div class="hidden md:flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Kundendaten
                </h2>
                <button @click="openCustomerModal = true"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Kunden ändern
                </button>
            </div>
            <livewire:offer.customer-data :offerId="$offerWithDetails->offer_id" />
            
            <!-- Kunden-Auswahl Modal - TELEPORTIERT AN BODY -->
            <div x-show="openCustomerModal" @customer-updated.window="openCustomerModal = false"
                 x-init="$watch('openCustomerModal', value => {
                     if (value) {
                         document.body.appendChild($el);
                         document.body.style.overflow = 'hidden';
                     } else {
                         document.body.style.overflow = 'auto';
                     }
                 })"
                 class="fixed bg-gray-900 bg-opacity-80" 
                 style="display: none; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999;" x-cloak>
                <div class="bg-white rounded-lg shadow-xl flex flex-col"
                     style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 95%; max-width: 800px; height: 90vh; max-height: 450px; z-index: 1000000;"
                     @click.away="openCustomerModal = false">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Kunden auswählen
                            </h2>
                            <button @click="openCustomerModal = false" 
                                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="flex-1 p-6 overflow-hidden">
                        <div class="h-full overflow-y-auto">
                            <livewire:customer.search-list :offerId="$offerWithDetails->offer_id" />
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex justify-end">
                            <button @click="openCustomerModal = false" 
                                    class="px-6 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Schließen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
                </div>
                <!-- Beschreibung/Kommentar (rechts 1/2) -->
                <div class="md:border-l md:border-gray-200/60 md:pl-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Zusätzliche Informationen
                    </h2>
                    <livewire:offer.comment-description :offerId="$offerWithDetails->id" />
                </div>
            </div>
        </div>

        <!-- Angebotsdetails Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Angebotsdetails
            </h2>
            <livewire:offer.offerdetails :offerId="$offerWithDetails->id" />
        </div>

        

        <!-- Positionen Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg mb-6 mt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Positionen
            </h2>
            <livewire:offerpositions-table :offerId="$offerWithDetails->offer_id" />
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
                                    <span class="text-sm font-medium text-green-700">zzgl. Umsatzsteuer ({{ $offerWithDetails->taxrate }} %)</span>
                                </div>
                                <span class="text-base font-semibold text-green-900">{{ number_format($total_price->total_price * ($offerWithDetails->taxrate / 100), 2, ',', '.') }} €</span>
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
                                <span class="text-lg font-bold text-purple-900">{{ number_format($total_price->total_price * (1 + ($offerWithDetails->taxrate / 100)), 2, ',', '.') }} €</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 
        @if(!empty($offerWithDetails->document_footer))
        <div class="mt-4 flex justify-end">
            <div class="w-full md:w-1/2 lg:w-1/2 xl:w-1/2 bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="prose prose-sm max-w-none text-gray-700">{!! $offerWithDetails->document_footer !!}</div>
            </div>
        </div>
        @endif
        --}}

    <script>
        document.addEventListener('comment-updated', (event) => {
            console.log(event.detail[0].message);
            // Alert entfernt - Erfolgsmeldung wird jetzt nur noch in der Komponente angezeigt
        });

        // Event-Listener für Angebotsdetails-Updates
        document.addEventListener('updateOfferSummary', (event) => {
            console.log('Angebot aktualisiert - Seite wird neu geladen für aktuelle Zusammenfassung');
            // Kurze Verzögerung, damit die Erfolgsmeldung noch sichtbar ist
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });
    </script>

    <!-- Floating Action Buttons - nur auf Smartphones -->
    <div class="md:hidden fixed bottom-6 right-6 z-50 flex flex-col space-y-3">
        <!-- Vorschau -->
        <button onclick="window.open('{{ route('createoffer.pdf', ['offer_id' => $offerWithDetails->offer_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}', '_blank')"
                class="flex items-center justify-center w-14 h-14 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-110"
                title="Vorschau">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </button>
        
        <!-- Zurück -->
        <a href="{{ route('offer.index') }}" 
           class="flex items-center justify-center w-12 h-12 bg-white/70 backdrop-blur-sm text-gray-700 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 border border-gray-300"
           title="Zurück zur Übersicht">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
            </svg>
        </a>
    </div>
</x-layout>
