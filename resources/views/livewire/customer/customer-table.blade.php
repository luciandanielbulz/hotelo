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
                <input wire:model.live="search" type="text" placeholder="Kunden suchen..." 
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Kundennummer</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kundenname / Firmenname</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Adresse</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">PLZ</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ort</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">E-Mail</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($customers as $customer)
                                <tr data-id="{{ $customer->id }}" class="hover:bg-indigo-100 cursor-pointer">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $customer->customer_number ?: $customer->id }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-black">
                                        {{ strlen($customer->customername ?? '') > 40 ? substr($customer->customername, 0, 40) . '...' : ($customer->customername ?? '') }}
                                        @if(!empty($customer->customername) && !empty($customer->companyname)) / @endif
                                        {{ strlen($customer->companyname ?? '') > 40 ? substr($customer->companyname, 0, 40) . '...' : ($customer->companyname ?? '') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ $customer->address }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ $customer->postalcode }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ $customer->location }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ $customer->email }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex flex-wrap gap-2 justify-end items-center">
                                            <!-- Bearbeiten Link -->
                                            <a href="{{ url('/customer/' . $customer->id . '/edit') }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Bearbeiten</a>

                                            <!-- + Angebot Link -->
                                            <a href="{{ url('/offer/create/' . $customer->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">+Angebot</a>

                                            <!-- + Rechnung Link -->
                                            <a href="{{ url('/invoice/create/' . $customer->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">+Rechnung</a>

                                            <!-- Löschen Button -->
                                            <form action="{{ url('/customer/' . $customer->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                class="text-red-600 hover:text-red-900 ml-4">
                                                    Löschen
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-4 text-sm text-gray-500 text-center">Keine Kunden gefunden</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Paginierungslinks -->
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div> 