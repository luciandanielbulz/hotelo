<x-layout>

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Liste der gesendeten Objekte</h1>
        </div>
    </div>
    <div class="sm:flex sm:items-center mt-4">
        <div class="sm:flex-auto">
            <form id="searchForm" class="form-inline flex w-1/3" method="GET" action="{{ route('outgoingemails.index') }}">
                <div class="sm:col-span-3">
                    <x-input name="search" type="text" placeholder="Suchen" label="" value="{{ request('search') }}" />
                </div>
                <div class="sm:col-span-2">
                    <x-button_submit value="Suchen" />
                </div>
            </form>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Art</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nummer</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kundenname/Firmenname</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Empfänger E-Mail</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Sendezeit</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Mit Anhang</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($outgoingEmails as $row)
                                @php
                                    $type = $row->type == 1 ? 'Rechnung' : 'Angebot';
                                    $fileLink = $row->filename;
                                    $customerName = $row->customername;
                                    $attachmentIcon = $row->withattachment ? 'fa-check' : 'fa-x';
                                    $attachmentColor = $row->withattachment ? 'text-success' : 'text-danger';
                                    $statusIcon = $row->status ? 'fa-check' : 'fa-x';
                                    $statusColor = $row->status ? 'text-success' : 'text-danger';
                                    $sendDate = new DateTime($row->sentdate);
                                    $sendDate->setTimezone(new DateTimeZone('Europe/Vienna'));
                                @endphp
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">{{ $type }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <a href="{{ $fileLink }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $row->objectnumber }}</a>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $customerName }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $row->getteremail }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">{{ $sendDate->format('Y-m-d H:i:s') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                        <i class="fa-solid {{ $attachmentIcon }} {{ $attachmentColor }}"></i>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                        <i class="fa-solid {{ $statusIcon }} {{ $statusColor }}"></i>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-4 text-sm text-gray-500 text-center">Keine Datensätze gefunden</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-layout>
