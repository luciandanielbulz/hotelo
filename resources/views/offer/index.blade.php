<x-layout>

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold text-gray-900">Angebote</h1>
                <p class="mt-2 text-sm text-gray-700">Eine Liste aller Angebote in Ihrem Konto, inklusive Nummer, Datum, Kunde und Beschreibung.</p>
            </div>
        </div>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <form id="searchForm" class="form-inline flex w-1/3" method="GET" action="{{ route('offer.index') }}">
                    <div class="sm:col-span-4">
                        <x-input name="search" type="text" placeholder="Suchen" label="" value="{{ request('search') }}" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-button_submit value="Suchen" />
                    </div>
                </form>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('customer.index') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">+ Neu</a>
            </div>
        </div>

        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Id</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nummer</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Datum</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kunde</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Beschreibung</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($offers as $offer)
                                    <tr data-id="{{ $offer->offer_id }}" class="hover:bg-indigo-100 cursor-pointer">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $offer->offer_id }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $offer->number }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($offer->date)->translatedFormat('d.m.Y') }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $offer->customername ?? $offer->companyname ?? 'Kein Kunde' }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $offer->description ?? 'Kein Kommentar' }}</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <div class="flex flex-wrap gap-2 justify-end items-center">
                                                <!-- Vorschau Button -->
                                                <button
                                                    onclick="window.open('{{ route('createoffer.pdf', ['offer_id' => $offer->offer_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}', '_blank')"
                                                    class="inline-flex items-center rounded-md bg-gray-300 px-3 h-8 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                    Vorschau
                                                </button>

                                                <!-- Bearbeiten Button -->
                                                <a href="{{ route('offer.edit', $offer->offer_id) }}"
                                                    class="inline-flex items-center rounded-md bg-gray-300 px-3 h-8 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                    Bearbeiten
                                                </a>

                                                <!-- Rechnung Button -->
                                                <a href="{{ route('invoice.createinvoicefromoffer', ['offerid' => $offer->offer_id]) }}"
                                                    class="inline-flex items-center rounded-md bg-gray-300 px-3 h-8 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                    Rechnung
                                                </a>

                                                <!-- PDF Button -->
                                                <button
                                                    onclick="window.open('{{ route('createoffer.pdf', ['offer_id' => $offer->offer_id, 'objecttype' => 'invoice', 'prev' => 'D']) }}', '_blank')"
                                                    class="inline-flex items-center rounded-md bg-gray-300 px-3 h-8 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                    PDF
                                                </button>

                                                <!-- Senden Form -->
                                                <form action="" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="objectid" value="{{ $offer->offer_id }}">
                                                    <input type="hidden" name="objecttype" value="offer">
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-md bg-gray-300 px-3 h-8 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                        Senden
                                                    </button>
                                                </form>

                                                <!-- Archiv Form -->
                                                <form action="" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="offerid" value="{{ $offer->offer_id }}">
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-md bg-gray-300 px-3 h-8 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                        Archiv
                                                    </button>
                                                </form>
                                            </div>
                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-3 py-4 text-sm text-gray-500 text-center">Keine Datensätze gefunden</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $offers->links() }}
        </div>

</x-layout>
