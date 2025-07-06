<x-layout>

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold text-gray-900">Angebote</h1>
                <p class="mt-2 text-sm text-gray-700">Eine Liste aller Angebote in Ihrem Konto, inklusive Nummer, Datum, Kunde und Beschreibung.</p>
            </div>
        </div>
        <div class="sm:flex sm:items-center sm:justify-end mt-8">
            <div class="sm:flex-none">
                <a href="{{ route('customer.index') }}" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">+ Neu</a>
            </div>
        </div>

        <livewire:offer.positiontable/>




</x-layout>
