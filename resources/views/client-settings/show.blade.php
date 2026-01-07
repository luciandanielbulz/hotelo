<x-layout>
    <x-slot:heading>
        Client-Einstellungen: {{ $client->clientname }}
    </x-slot:heading>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Zurück-Button -->
        <div class="mb-6">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Zurück zur Übersicht
            </a>
        </div>

        <!-- Client-Informationen -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Client-Informationen</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->clientname }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Firma</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->companyname }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Version</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->version }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($client->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktiv
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Archiviert
                                </span>
                            @endif
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statische Einstellungen -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statische Einstellungen</h3>

                <!-- Nummerierung -->
                <div class="mb-8">
                    <h4 class="text-base font-medium text-gray-900 mb-4">Nummerierung</h4>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Letzte Rechnungsnummer</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $clientSettings->lastinvoice }}</dd>
                            <p class="mt-1 text-sm text-orange-600"><strong>Nächste:</strong> {{ $clientSettings->lastinvoice + 1 }}</p>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Rechnung Multiplikator</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $clientSettings->invoicemultiplikator }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Letzte Angebotsnummer</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $clientSettings->lastoffer }}</dd>
                            <p class="mt-1 text-sm text-orange-600"><strong>Nächste:</strong> {{ $clientSettings->lastoffer + 1 }}</p>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Angebot Multiplikator</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $clientSettings->offermultiplikator }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Formatierung -->
                <div class="mb-8">
                    <h4 class="text-base font-medium text-gray-900 mb-4">Nummernformate</h4>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Rechnungsnummer-Format</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $clientSettings->invoice_number_format }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Angebots-Nummer-Format</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $clientSettings->offer_number_format ?? 'Verwendet Rechnungsformat' }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Präfixe -->
                <div class="mb-8">
                    <h4 class="text-base font-medium text-gray-900 mb-4">Präfixe</h4>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Rechnungs-Präfix</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $clientSettings->invoice_prefix ?? 'Kein Präfix' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Angebots-Präfix</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $clientSettings->offer_prefix ?? 'Kein Präfix' }}</dd>
                        </div>
                    </div>
                </div>

                <!-- System-Einstellungen -->
                <div class="mb-8">
                    <h4 class="text-base font-medium text-gray-900 mb-4">System-Einstellungen</h4>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Maximale Upload-Größe</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $clientSettings->max_upload_size }} MB</dd>
                        </div>
                    </div>
                </div>

                <!-- Nummern-Vorschau -->
                <div class="mb-8">
                    <h4 class="text-base font-medium text-gray-900 mb-4">Vorschau der nächsten Nummern</h4>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Nächste Rechnungsnummer:</dt>
                                <dd class="mt-1 text-lg font-mono text-blue-900">{{ $clientSettings->generateInvoiceNumber() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Nächste Angebotsnummer:</dt>
                                <dd class="mt-1 text-lg font-mono text-blue-900">{{ $clientSettings->generateOfferNumber() }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bearbeiten-Link (falls Admin-Berechtigung vorhanden) -->
                @if(auth()->user()->hasPermission('edit_client_settings'))
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600">Als Administrator können Sie diese Einstellungen bearbeiten.</p>
                            <a href="{{ route('client-settings.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Einstellungen bearbeiten
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout> 