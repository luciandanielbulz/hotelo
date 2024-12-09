
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold text-gray-900">Positionen</h1>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none space-x-4">
                <button type="button" wire:click="addPosition" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">+ Position</button>
                <button type="button" wire:click="addTextPosition" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">+ Textposition</button>
            </div>
        </div>
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Pos</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Menge</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Einheit</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-3/5">Beschreibung</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Preis/EH</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Gesamtpreis</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 w-20">
                                        <span class="sr-only">Bearbeiten</span>
                                    </th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 w-20">
                                        <span class="sr-only">Löschen</span>
                                    </th>
                                </tr> 
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white" id="positionsTable">
                                @forelse ($positions as $position)
                                    <tr data-id="{{ $position->id }}">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $position->id }}</td>
                                        @if ($position->positiontext == false)
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($position->amount, 2, ',', '.') }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $position->unit_name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-3/5">{{ $position->designation }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($position->price, 2, ',', '.') }} €</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($position->price * $position->amount, 2, ',', '.') }} €</td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 w-20">
                                                <a href="{{ route('offerposition.edit', $position->id) }}" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600">Bearbeiten</a>
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 w-20">
                                                <button wire:click="deletePosition({{ $position->id }})" class="inline-block rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Löschen</button>
                                            </td>
                                        @else
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><b>P</b></td>
                                            <td></td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-900 w-3/5"><b>{{ $position->details }}</b></td>
                                            <td></td>
                                            <td></td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 w-20">
                                                <a href="{{ route('offerposition.edit', $position->id) }}" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600">Bearbeiten</a>
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 w-20">
                                                <button wire:click="deletePosition({{ $position->id }})" class="inline-block rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Löschen</button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">Keine Positionen gefunden</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
