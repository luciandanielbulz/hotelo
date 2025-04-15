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
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-black sm:pl-6">{{ $offer->offer_id }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ $offer->number }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ \Carbon\Carbon::parse($offer->date)->translatedFormat('d.m.Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">
                                    {{ str($offer->customername ?: $offer->companyname ?: 'Kein Kunde')->limit(40) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ $offer->description ?? 'Kein Kommentar' }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <div class="flex flex-wrap gap-2 justify-end items-center">
                                        <!-- Vorschau Button -->
                                        <a href="{{ route('createoffer.pdf', ['offer_id' => $offer->offer_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Vorschau</a>
                                        

                                        <!-- Bearbeiten Button -->
                                        <a href="{{ route('offer.edit', $offer->offer_id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Bearbeiten</a>
                                    

                                        <!-- Rechnung Button -->
                                        <a href="{{ route('invoice.createinvoicefromoffer', ['offerid' => $offer->offer_id]) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Rechnung</a>

                                        <!-- PDF Button -->
                                        <a href="{{ route('createoffer.pdf', ['offer_id' => $offer->offer_id, 'objecttype' => 'invoice', 'prev' => 'D']) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">PDF</a>
                                        
                                        <!-- Archivieren Button -->
                                        <a href=wire:click="archiveOffer({{ $offer->offer_id }})" class="text-indigo-600 hover:text-orange-900 ml-4">Archiv</a>
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

<!-- Paginierungslinks -->
<div class="mt-4">
    {{ $offers->links() }}
</div>
</div>
