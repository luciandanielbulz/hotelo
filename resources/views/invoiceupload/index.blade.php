<x-layout>
    <!-- Alpine.js-Scope um den gesamten relevanten Bereich erweitert -->
    <div x-data="{ open: false }" x-cloak>
      <!-- Modal: nur Schließen-Button -->
      <div
        x-show="open"
        class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50"
        style="display: none;">
        <div class="bg-white p-6 rounded-md shadow-lg w-1/3">
          <h3 class="text-lg font-semibold">Wählen Sie einen Monat</h3>
          <ul class="mt-4">
            @if($months->isEmpty())
              <li class="text-gray-500">Keine Monate verfügbar</li>
            @else
            @foreach($months as $month)
                <button
                @click="window.location.href='{{ route('invoiceupload.downloadZipForMonth', ['month' => $month]) }}'"
                class="bg-indigo-50 border border-indigo-200 rounded-md py-2 px-4 text-center hover:bg-indigo-100 transition">
                {{ $month }}
                </button>
            @endforeach
            @endif
          </ul>
          <div class="mt-4 text-right">
            <button
              @click="open = false"
              class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Schließen
            </button>
          </div>
        </div>
      </div>

      <!-- Restlicher Seiteninhalt -->
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-base font-semibold text-gray-900">Hochgeladene Rechnungen</h1>
          <p class="mt-2 text-sm text-gray-700">Eine Übersicht aller bisher hochgeladenen Rechnungen.</p>
        </div>
      </div>

      <!-- Suchformular & Buttons -->
      <div class="sm:flex sm:items-center mt-4">
        <div class="sm:flex-auto">
          <form id="searchForm" class="form-inline flex w-1/3" method="GET" action="{{ route('invoiceupload.index') }}">
            <div class="sm:col-span-3">
              <x-input name="search" type="text" placeholder="Suchen" label="" value="{{ request('search') }}" />
            </div>
            <div class="sm:col-span-2 mt-1">
              <x-button_submit value="Suchen" />
            </div>
          </form>
        </div>

        <x-button route="{{ route('invoiceupload.upload.create') }}" value="+ Neu" />
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
          <!-- Button zum Öffnen des Modals liegt jetzt im Alpine-Scope -->
          <button
            @click="open = true"
            class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600">
            Monat auswählen
          </button>
        </div>
      </div>

      <!-- Angepasster Tabellenbereich -->
      <div class="mt-8 flow-root w-full">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
              @if($invoiceuploads->isEmpty())
                <p class="text-gray-600 p-4">Keine Rechnungen gefunden.</p>
              @else
                <table class="min-w-full divide-y divide-gray-300">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black break-words">
                          {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black break-words">
                          {{ $invoice->invoice_vendor ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black break-words">
                          {{ $invoice->description ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black break-words">
                          {{ $invoice->invoice_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                          <a href="{{ route('invoiceupload.edit', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">
                            Bearbeiten
                          </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                          <a href="{{ route('invoiceupload.show_invoice', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">
                            Rechnung anzeigen
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif
            </div>
          </div>
        </div>
        @if(!$invoiceuploads->isEmpty())
          <div class="mt-4">
            {{ $invoiceuploads->links() }}
          </div>
        @endif
      </div>
    </div>
  </x-layout>
