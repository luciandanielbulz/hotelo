<x-layout>
    <x-slot:heading>
        Statische Client-Einstellungen
    </x-slot:heading>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        <!-- Hauptformular -->
        <form method="POST" action="{{ route('client-settings.update') }}">
            @csrf
            @method('PUT')

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <!-- Warnung -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Wichtiger Hinweis</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Diese Einstellungen sind <strong>NICHT versioniert</strong> und betreffen ALLE Versionen dieses Clients!</p>
                                    <p class="mt-1">Änderungen werden sofort wirksam und sind <strong>NICHT rückgängig</strong> machbar!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nummerierung -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Nummerierung</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <x-input name="lastinvoice" type="number" placeholder="Letzte Rechnungsnummer" label="Letzte Rechnungsnummer" value="{{ old('lastinvoice', $clientSettings->lastinvoice) }}" />
                                <p class="mt-1 text-sm text-orange-600"><strong>Achtung:</strong> Nächste Rechnungsnummer wird {{ $clientSettings->lastinvoice + 1 }}</p>
                            </div>

                            <div>
                                <x-input name="lastoffer" type="number" placeholder="Letzte Angebotsnummer" label="Letzte Angebotsnummer" value="{{ old('lastoffer', $clientSettings->lastoffer) }}" />
                                <p class="mt-1 text-sm text-orange-600"><strong>Achtung:</strong> Nächste Angebotsnummer wird {{ $clientSettings->lastoffer + 1 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hinweis zu Nummernformaten und Präfixen -->
                    <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Nummernformate und Präfixe</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Nummernformate und Präfixe werden jetzt in den <a href="{{ route('clients.my-settings') }}" class="font-medium underline hover:text-blue-800">Firmen-Einstellungen</a> verwaltet.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System-Einstellungen -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">System-Einstellungen</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <x-input name="max_upload_size" type="number" placeholder="2048" label="Maximale Upload-Größe (MB)" value="{{ old('max_upload_size', $clientSettings->max_upload_size) }}" />
                                <p class="mt-1 text-sm text-gray-600">Maximale Dateigröße für Dokument-Uploads</p>
                            </div>
                        </div>
                    </div>

                    <!-- Aktionen -->
                    <div class="flex items-center justify-end gap-x-6 pt-6">
                        <a href="{{ route('clients.my-settings') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                            Einstellungen speichern
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout> 