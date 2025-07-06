<x-layout>

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold text-gray-900">Angebote</h1>
                <p class="mt-2 text-sm text-gray-700">Eine Liste aller Angebote in Ihrem Konto, inklusive Nummer, Datum, Kunde und Beschreibung.</p>
            </div>
        </div>
        <div class="sm:flex sm:items-center mt-8">
            <div class="sm:flex-auto">
                <form id="searchForm" class="form-inline flex w-1/3" method="GET" action="{{ route('offer.index') }}">
                    <div class="sm:col-span-4">
                        <x-input name="search" type="text" placeholder="Suchen" label="" value="{{ request('search') }}" />
                    </div>
                    <div class="sm:col-span-2 mt-1">
                        <x-button_submit value="Suchen" />
                    </div>
                </form>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('customer.index') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">+ Neu</a>
            </div>
        </div>

        <livewire:offer.positiontable/>




</x-layout>
