<x-layout>
    <div class="container">
        <div class="row p-3">
            <div class="col text-left">
                <h1>Liste der gesendeten Objekte</h1>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row" id="offerList">
            <div class="col-md-4">
                <form id="searchForm" class="form-inline" method="GET" action="{{ route('outgoingemails.index') }}">
                    <input type="text" id="searchInput" name="search" class="form-control mr-2" placeholder="Suchen" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-secondary">Suchen</button>
                </form>
            </div>
            <div class="col"></div>
        </div>
    </div>

    <div class="container">
        <table class="table table-sm mt-3">
            <thead>
                <tr>
                    <th>Art</th>
                    <th>Nummer</th>
                    <th>Kundenname/Firmenname</th>
                    <th>Empfänger E-Mail</th>
                    <th class="text-center">Sendezeit</th>
                    <th class="text-center">Mit Anhang</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
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
                        <td class="align-middle">{{ $type }}</td>
                        <td class="align-middle">
                            <a href="{{ $fileLink }}" target="_blank">{{ $row->objectnumber }}</a>
                        </td>
                        <td class="align-middle">{{ $customerName }}</td>
                        <td class="text-left align-middle">{{ $row->getteremail }}</td>
                        <td class="text-center align-middle">{{ $sendDate->format('Y-m-d H:i:s') }}</td>
                        <td class="text-center align-middle">
                            <i class="fa-solid {{ $attachmentIcon }} {{ $attachmentColor }}"></i>
                        </td>
                        <td class="text-center align-middle">
                            <i class="fa-solid {{ $statusIcon }} {{ $statusColor }}"></i>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-left">Keine Datensätze gefunden</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
