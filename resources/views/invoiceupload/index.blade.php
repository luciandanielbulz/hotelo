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
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
          <!-- Button zum Öffnen des Modals liegt jetzt im Alpine-Scope -->
          <button
            @click="open = true"
            class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600">
            ZIP Download
          </button>
        </div>
      </div>

      <!-- Livewire-Tabelle -->
      <livewire:invoice-upload.invoice-upload-table />
    </div>
  </x-layout>
