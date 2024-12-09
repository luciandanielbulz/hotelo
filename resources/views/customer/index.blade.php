<x-layout>

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Kunden</h1>
            <p class="mt-2 text-sm text-gray-700">Eine Liste aller Kunden in Ihrem Konto, inklusive Name, Adresse, E-Mail und mehr.</p>
        </div>
    </div>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <form id="searchForm" class="form-inline flex w-1/3" method="GET" action="{{ route('customer.index') }}">

                <div class="sm:col-span-3">
                    <x-input name="search" type="text" placeholder="Suchen" label="" value="{{ request('search') }}" />
                </div>
                <div class="sm:col-span-2">
                    <x-button_submit value="Suchen" />
                </div>

            </form>
        </div>

        <x-button route="{{ route('customer.create') }}" value="+ Neu" />


    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">K-Nr</th>
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
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $customer->id }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $customer->customername ?? '' }}
                                        @if(!empty($customer->customername) && !empty($customer->companyname)) / @endif
                                        {{ $customer->companyname ?? '' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $customer->address }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $customer->postalcode }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $customer->location }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $customer->email }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex flex-wrap gap-2 justify-end items-center">
                                            <!-- Bearbeiten Link -->
                                            <a href="{{ url('/customer/' . $customer->id . '/edit') }}"
                                                class="inline-flex items-center rounded-md bg-gray-300 px-3 h-8 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                Bearbeiten
                                            </a>

                                            <!-- + Angebot Link -->
                                            <a href="{{ url('/offer/create/' . $customer->id) }}"
                                                class="inline-flex items-center rounded-md bg-gray-300 px-3 h-8 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                + Angebot
                                            </a>

                                            <!-- + Rechnung Link -->
                                            <a href="{{ url('/invoice/create/' . $customer->id) }}"
                                                class="inline-flex items-center rounded-md bg-gray-300 px-3 h-8 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">
                                                + Rechnung
                                            </a>

                                            <!-- Löschen Button -->
                                            <form action="{{ url('/customer/' . $customer->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-md bg-red-600 px-3 h-8 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
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
    <div class="mt-4">
        {{ $customers->links() }}
    </div>


</x-layout>
