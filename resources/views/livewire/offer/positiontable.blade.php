<div>
    <!-- Suchfeld -->
    <div class="mb-6">
        <div class="w-1/4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input wire:model.live="search" type="text" placeholder="Angebote suchen..." 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm bg-white text-gray-900 placeholder-gray-500 sm:text-sm">
            </div>
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
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">versand am</th>
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
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">
                                    {{ $offer->sent_date ? \Carbon\Carbon::parse($offer->sent_date)->translatedFormat('d.m.Y H:i') : '-' }} 
                                </td>
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
                                        
                                        @if(auth()->user()->hasPermission('send_emails'))
                                            <!-- Senden Form -->
                                            <form action="{{ route('offer.sendmail') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="objectid" value="{{ $offer->offer_id }}">
                                                <input type="hidden" name="objecttype" value="offer">
                                                <button type="submit"
                                                    class="text-indigo-600 hover:text-orange-900 ml-4">
                                                    Senden
                                                </button>
                                            </form>
                                        @endif

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
</div>
