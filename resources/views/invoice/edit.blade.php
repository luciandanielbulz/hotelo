<x-layout>
    <!-- Optimierter Header -->
    <div class="mb-6">
        <!-- Mobile Header - zentriert -->
        <div class="md:hidden text-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Rechnung bearbeiten</h1>
            <p class="text-gray-600 mt-1">Rechnung #<span class="js-invoice-number">{{ $invoice->number }}</span> vom {{ \Carbon\Carbon::parse($invoice->date)->translatedFormat('d.m.Y') }}</p>
        </div>
        
        <!-- Desktop Header - mit Buttons -->
        <div class="hidden md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Rechnung bearbeiten</h1>
                <p class="text-gray-600">Rechnung #<span class="js-invoice-number">{{ $invoice->number }}</span> vom {{ \Carbon\Carbon::parse($invoice->date)->translatedFormat('d.m.Y') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('invoice.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/70 backdrop-blur-sm text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                    </svg>
                    <span class="hidden lg:inline">Zurück zur Übersicht</span>
                    <span class="lg:hidden">Zurück</span>
                </a>
                @if(auth()->user()->hasPermission('send_emails'))
                    <form action="{{ route('invoice.sendmail') }}" method="POST">
                        @csrf
                        <input type="hidden" name="objectid" value="{{ $invoice->id }}">
                        <input type="hidden" name="objecttype" value="invoice">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 bg-purple-500 text-white font-semibold rounded-lg hover:bg-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Senden
                        </button>
                    </form>
                @endif
                <button onclick="window.open('{{ route('createinvoice.pdf', ['invoice_id' => $invoice->id]) }}', '_blank')"
                        class="inline-flex items-center px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl">
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
        <!-- Kundendaten, Status, Beschreibung in gemeinsamer Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg mb-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Kundendaten Karte (1/3 im LG, 1/2 im MD) -->
            <div class="md:col-span-1 lg:col-span-1">
                <div class="h-full" x-data="{ openCustomerModal: false }">
            <!-- Mobile Header -->
            <div class="md:hidden mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="hidden lg:inline">Kundendaten</span>
                    <span class="lg:hidden">Kunden</span>
                </h2>
                <div class="mt-3 text-center">
                    <button @click="openCustomerModal = true"
                            class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-600 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span class="hidden lg:inline">Kunden ändern</span>
                        <span class="lg:hidden">Ändern</span>
                    </button>
                </div>
            </div>
            
            <!-- Desktop Header -->
            <div class="hidden md:flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="hidden lg:inline">Kundendaten</span>
                    <span class="lg:hidden">Kunden</span>
                </h2>
                <button @click="openCustomerModal = true"
                        class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-600 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span class="hidden lg:inline">Kunden ändern</span>
                    <span class="lg:hidden">Ändern</span>
                </button>
            </div>
            <livewire:customer.customer-data :invoiceId="$invoice->id">
            
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
                     style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 800px; height: 450px; max-height: 80vh; z-index: 1000000;"
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
                            <livewire:customer.search-list :invoiceId="$invoice->id" />
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
            </div>
            <!-- Mitte (1/3 im LG, 1/2 im MD): Status -->
            <div class="md:col-span-1 lg:col-span-1 md:border-l md:border-gray-200/60 md:pl-6 lg:border-l lg:pl-6">
                <div class="h-full">
                    <livewire:invoice.status-select :invoiceId="$invoice->id" />
                </div>
            </div>
            <!-- Rechts (1/3 im LG, 1/2 im MD): Beschreibung Intern -->
            <div class="md:col-span-1 lg:col-span-1 md:border-l md:border-gray-200/60 md:pl-6 lg:border-l lg:pl-6">
                <div class="h-full">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <span class="hidden lg:inline">Beschreibung (intern)</span>
                        <span class="lg:hidden">Beschreibung</span>
                    </h2>
                    <livewire:invoice.comment-description :invoiceId="$invoice->id" />
                </div>
            </div>
            </div>
        </div>
        </div>

        <!-- Rechnungsdetails Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Rechnungsdetails
            </h2>
            <livewire:invoice.invoicedetails :invoiceId="$invoice->id"/>
        </div>

        

        <!-- Positionen Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Positionen
            </h2>
            <livewire:invoicepositions-table :invoiceId="$invoice->id"/>
        </div>

        <!-- Berechnungen und Anzahlung Karte -->
        <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
                Zusammenfassung
            </h2>
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Anzahlungsbereich (1. Drittel) -->
                <div class="md:col-span-1">
                    <livewire:invoice.depositamount :invoiceId="$invoice->id"/>
                </div>
                <!-- Summenbereich (2. und 3. Drittel) -->
                <div class="md:col-span-2 md:border-l md:border-gray-200/60 md:pl-6">
                    <livewire:invoice.calculations :invoiceId="$invoice->id"/>
                </div>
            </div>
        </div>

        

        

    </div>
    <!-- Dokument-Fußzeile (Editor) - eigener Abstand unter der Zusammenfassung -->
    <div class="mt-6">
        <livewire:invoice.document-footer :invoiceId="$invoice->id" />
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

        // Live-Aktualisierung der Rechnungsnummer im Header
        document.addEventListener('invoiceNumberChanged', (event) => {
            try {
                const newNumber = event.detail.number ?? '';
                document.querySelectorAll('.js-invoice-number').forEach(el => {
                    el.textContent = newNumber;
                });
            } catch (e) {
                console.warn('Konnte Header-Rechnungsnummer nicht aktualisieren:', e);
            }
        });
    </script>
    <script></script>

    <!-- Floating Action Buttons - nur auf Smartphones -->
    <div class="md:hidden fixed bottom-6 right-6 z-50 flex flex-col space-y-3">
        @if(auth()->user()->hasPermission('send_emails'))
        <!-- Senden -->
        <form action="{{ route('invoice.sendmail') }}" method="POST">
            @csrf
            <input type="hidden" name="objectid" value="{{ $invoice->id }}">
            <input type="hidden" name="objecttype" value="invoice">
            <button type="submit"
                    class="flex items-center justify-center w-14 h-14 bg-purple-500 text-white rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-110"
                    title="Senden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </button>
        </form>
        @endif
        <!-- Vorschau -->
        <button onclick="window.open('{{ route('createinvoice.pdf', ['invoice_id' => $invoice->id]) }}', '_blank')"
                class="flex items-center justify-center w-14 h-14 bg-blue-500 text-white rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-110 hover:bg-blue-600"
                title="Vorschau">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </button>
        
        <!-- Zurück -->
        <a href="{{ route('invoice.index') }}" 
           class="flex items-center justify-center w-12 h-12 bg-white/70 backdrop-blur-sm text-gray-700 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 border border-gray-300"
           title="Zurück zur Übersicht">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
            </svg>
        </a>
    </div>
</x-layout>
