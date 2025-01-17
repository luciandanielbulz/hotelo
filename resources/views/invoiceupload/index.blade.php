<x-layout>

    <!-- Titel & Einleitung -->
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Hochgeladene Rechnungen</h1>
            <p class="mt-2 text-sm text-gray-700">Eine Übersicht aller bisher hochgeladenen Rechnungen.</p>
        </div>
    </div>

    <!-- Alpine.js-Scope -->
    <div x-data="{ open: false }" x-cloak>

        <!-- Suchformular & Buttons -->
        <div class="sm:flex sm:items-center mt-4">
            <div class="sm:flex-auto">
                <form id="searchForm" class="form-inline flex w-1/3" method="GET" action="{{ route('invoiceupload.index') }}">
                    <x-input name="search" type="text" placeholder="Suchen" label="" value="{{ request('search') }}" />
                    <div class="sm:col-span-2 ml-2 mt-2">
                        <x-button_submit value="Suchen" />
                    </div>
                </form>
            </div>
            <x-button route="{{ route('invoiceupload.upload.create') }}" value="+ Neu" />
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <button
                    @click="open = true"
                    class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600"
                >
                    Monat auswählen
                </button>
            </div>
        </div>

        <!-- Modal: nur Schließen-Button -->
        <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50" style="display: none;">
            <div class="bg-white p-6 rounded-md shadow-lg w-1/3">
                <h3 class="text-lg font-semibold">Wählen Sie einen Monat</h3>
                <ul class="mt-4">
                    @if($months->isEmpty())
                        <li class="text-gray-500">Keine Monate verfügbar</li>
                    @else
                        @foreach($months as $month)
                            <li>
                                <button @click="window.location.href='{{ route('invoiceupload.downloadZipForMonth', ['month' => $month]) }}'">
                                    {{ $month }}
                                </button>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <div class="mt-4 text-right">
                    <button @click="open = false" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Schließen</button>
                </div>
            </div>
        </div>

        <!-- Tabelle mit Rechnungen -->
        <div class="mt-5 grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-9">
            <div class="sm:col-span-1 md:col-span-5">
                @if($invoiceuploads->isEmpty())
                    <p class="text-gray-600">Keine Rechnungen gefunden.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rechnungsdatum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lieferant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beschreibung</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rechnungsnummer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoiceuploads as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $invoice->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $invoice->invoice_vendor ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $invoice->description ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $invoice->invoice_number ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('invoiceupload.edit', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">Bearbeiten</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('invoiceupload.show_invoice', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">Rechnung anzeigen</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $invoiceuploads->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layout>
