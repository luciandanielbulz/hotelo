<x-layout>
    <div class="max-w-4xl mx-auto py-10">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">Rechnungsdetails</h2>
            <p class="mt-1 text-sm text-gray-600">
                Detaillierte Informationen zur ausgewählten Rechnung.
            </p>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Rechnung #{{ $invoice->id }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Details zur Rechnung.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <!-- Rechnungsdatum -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Rechnungsdatum
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}
                        </dd>
                    </div>

                    <!-- Beschreibung -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Beschreibung
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $invoice->description ?? 'Keine Beschreibung vorhanden.' }}
                        </dd>
                    </div>

                    <!-- Rechnungsnummer -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Rechnungsnummer
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $invoice->invoice_number ?? 'Keine Rechnungsnummer vorhanden.' }}
                        </dd>
                    </div>

                    <!-- PDF-Download-Link -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            PDF
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($invoice->filepath)
                                <a href="{{ Storage::url($invoice->filepath) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                    Rechnung als PDF anzeigen
                                </a>
                            @else
                                Keine PDF-Datei vorhanden.
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('invoiceupload.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900">
                &larr; Zurück zur Übersicht
            </a>
        </div>
    </div>
</x-layout>
