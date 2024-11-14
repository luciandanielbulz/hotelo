<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Angebot {{ $offercontent->number }}</title>

</head>
<body>
    <table border="1" cellpadding="0" cellspacing="3" style="width: 100%;">
        <tr>

            <td style="text-align: right;">
                {{$client->companyname}}<br>
                Gewerbe Programmierdiensleistungen und Handel<br>
                {{$client->address}}<br>
                {{$client->postalcode}} {{$client->location}}<br>
                {{$client->email}}<br>
                {{$client->phone}}
                <br><br>
                Gültigkeit: 3 Wochen<br>
                Ihr Ansprechpartner:  {{$client->clientname}}<br>
                Zahlungskonditionen:  {{$condition->conditionname}}<br>
                Ihre UID-Nummer:  {{$customer->vat_number}}<br><br>
                <!--Angebotsnummer '.$Objekt_nummer.'<br>
                '.$objectname.'sdatum: '.$Objekt_datum.'<br>
                Lieferdatum: '.$lieferdatum.'<br>-->
                Angebotsdatum: datum
            </td>
        </tr>
    </table>
    <table border="1">
        <tr>
            <td colspan="3">
                {{$customer->companyname}}<br>
                {{$customer->customername}}<br>
                {{$customer->address}}<br>
                {{$customer->postalcode}} {{$customer->location}}
                {{$customer->country}}
            </td>
        </tr>

        <tr>
            <td style="font-size:1.3em; font-weight: bold;">
                <br><br>
                Angebot {{$offercontent->number}}
            </td>
        </tr>
    </table>

    <!-- Objektdetails -->
    <div>
        <p><strong>Angebot Datum:</strong> {{ $offercontent->date }}</p>
        <p><strong>Lieferdatum:</strong> {{ $offercontent->periodfrom }}</p>
        <p><strong>Zahlungskonditionen:</strong> {{ "" }}</p>
    </div>

    <!-- Beschreibung -->
    <p>{{ $offercontent->description }}</p>

    <!-- Positionen -->
    <table class="content-table">
        <thead>
            <tr>
                <th>Menge</th>
                <th>Einheit</th>
                <th>Bezeichnung</th>
                <th>Einzelpreis</th>
                <th>Betrag</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($positions as $position)
                <tr>
                    <td>{{ $position->amount }}</td>
                    <td>{{ $position->unit_id }}</td>
                    <td>{{ $position['Designation'] }}</td>
                    <td>{{ number_format($position['Price'], 2, ',', '.') }} €</td>
                    <td>{{ number_format($position['Amount'] * $position['Price'], 2, ',', '.') }} €</td>
                </tr>
                @if (!empty($position['Details']))
                <tr>
                    <td colspan="5">{{ $position['Details'] }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <!-- Summen -->
    <div>
        <p><strong>Zwischensumme:</strong> {{ number_format(222, 2, ',', '.') }} €</p>
        <p><strong>Umsatzsteuer ({{ 21 }}%):</strong> {{ number_format(20, 2, ',', '.') }} €</p>
        <p><strong>Gesamtsumme:</strong> {{ number_format(13, 2, ',', '.') }} €</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>{{ $client->web_page }}, {{ $client->address }}, {{ $client->postalcode }} {{ $client->location }}</p>
        <p>Tel: {{ $client->phone }}, E-Mail: {{ $client->email }}</p>
        <p>{{ $client->bank }}, IBAN: {{ $client->accountnumber }}, BIC: {{ $client->bic }}</p>
    </div>
</body>
</html>
