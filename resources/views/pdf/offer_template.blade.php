<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angebot {{ $offer->number }}</title>
    <style>
        @page {
            margin: 10mm;
            @bottom-center {
                content: "Seite " counter(page) " von " counter(pages);
                font-size: 8px;
                color: grey;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        
        .header {
            width: 100%;
            position: relative;
            height: 80px;
            margin-bottom: 20px;
        }
        
        .logo {
            position: absolute;
            top: 0;
            left: 0;
            max-height: 30px;
            max-width: 150px;
        }
        
        .company-info {
            position: absolute;
            top: 35px;
            left: 0;
            font-size: 7px;
            color: black;
        }
        
        .document-info {
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            text-align: right;
        }
        
        .document-info table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        .document-info td {
            padding: 2px 0;
            border: none;
        }
        
        .customer-address {
            margin: 20px 0;
            width: 50%;
        }
        
        .customer-address table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .customer-address td {
            padding: 2px 0;
            border: none;
        }
        
        .operation-info {
            position: absolute;
            top: 120px;
            right: 0;
            width: 200px;
            font-size: 10px;
        }
        
        .operation-info table {
            width: 100%;
            border-collapse: collapse;
            border: 0.5px solid white;
        }
        
        .operation-info td {
            padding: 2px 5px;
        }
        
        .document-title {
            font-size: 20px;
            font-weight: bold;
            margin: 30px 0 15px 0;
            color: {{ $client->color ?? '#000000' }};
        }
        
        .intro-text {
            margin: 15px 0;
            font-size: 10px;
        }
        
        .positions-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .positions-header {
            background-color: {{ $client->color ?? '#000000' }};
            color: {{ $client->color !== '#000000' ? 'white' : 'black' }};
            font-weight: bold;
        }
        
        .positions-header td {
            padding: 5px;
            font-size: 10px;
        }
        
        .positions-body td {
            padding: 5px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }
        
        .position-details {
            font-size: 9px;
            color: #666;
            white-space: pre-line;
            margin-top: 3px;
        }
        
        .position-text-only {
            text-align: center;
            font-weight: bold;
            white-space: pre-line;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-top: 0.5px solid {{ $client->color ?? '#000000' }};
        }
        
        .totals-table td {
            padding: 3px 5px;
        }
        
        .total-final {
            font-weight: bold;
            color: {{ $client->color ?? '#000000' }};
            font-size: 11px;
        }
        
        .signature-section {
            margin-top: 40px;
            font-size: 10px;
        }
        
        .signature-line {
            margin-top: 30px;
            border-bottom: 1px solid black;
            width: 300px;
            height: 20px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 25mm;
            font-size: 8px;
            color: grey;
        }
        
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .footer-table td {
            vertical-align: top;
            width: 25%;
            padding: 2px;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @if($client->logo)
            <img src="{{ storage_path('app/public/logos/' . $client->logo) }}" class="logo" alt="Logo">
        @endif
        
        <div class="company-info">
            {{ $client->companyname }} - {{ $client->address }} - {{ $client->postalcode }} {{ $client->location }}
        </div>
        
        <div class="document-info">
            <table>
                <tr>
                    <td>Angebots-Nr.</td>
                    <td class="text-right"><strong>{{ $offer->number }}</strong></td>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <td>Angebotsdatum</td>
                    <td class="text-right">{{ $formattedDate }}</td>
                </tr>
                <tr>
                    <td>Gültigkeitsdauer</td>
                    <td class="text-right">3 Wochen</td>
                </tr>
            </table>
        </div>
    </div>
    
    <!-- Customer Address -->
    <div class="customer-address">
        <table>
            <tr><td>{{ $customer->companyname ?? '' }}</td></tr>
            <tr><td>{{ $customer->customername ?? '' }}</td></tr>
            <tr><td>{{ $customer->address ?? '' }}</td></tr>
            <tr><td>{{ $customer->postalcode ?? '' }} {{ $customer->location ?? '' }}</td></tr>
            <tr><td>{{ $customer->country ?? '' }}</td></tr>
        </table>
    </div>
    
    <!-- Operation Info -->
    <div class="operation-info">
        <table>
            <tr>
                <td>Zahlungskonditionen</td>
                <td class="text-right">{{ $condition->conditionname ?? '' }}</td>
            </tr>
            <tr>
                <td>Ihre Kundennummer</td>
                <td class="text-right">{{ $customer->customer_number ?? '' }}</td>
            </tr>
            <tr>
                <td>Ihre USt-Id.</td>
                <td class="text-right">{{ $customer->vat_number ?? '' }}</td>
            </tr>
        </table>
    </div>
    
    <!-- Document Title -->
    <div class="document-title">
        Angebot {{ $offer->number }}
    </div>
    
    <!-- Intro Text -->
    <div class="intro-text">
        Unter Einhaltung unserer allg. Geschäftsbedingungen, erlauben wir uns, Ihnen folgendes Angebot zu unterbreiten:
    </div>
    
    <!-- Positions Table -->
    <table class="positions-table">
        <tr class="positions-header">
            <td style="width: 7%;">Pos.</td>
            <td style="width: 54%;">Bezeichnung</td>
            <td style="width: 12%;">Menge</td>
            <td style="width: 12%; text-align: right;">Einzelpreis</td>
            <td style="width: 15%; text-align: right;">Betrag</td>
        </tr>
        
        @php $positionNumber = 1; @endphp
        @foreach($positions as $position)
            @if($position->positiontext == 1)
                <tr class="positions-body">
                    <td colspan="5" class="position-text-only">
                        <strong>{!! nl2br(htmlspecialchars($position->details ?? '')) !!}</strong>
                    </td>
                </tr>
            @else
                <tr class="positions-body">
                    <td class="text-center">{{ $positionNumber }}.</td>
                    <td>
                        {{ $position->designation }}
                        @if($position->details)
                            <div class="position-details">{!! nl2br(htmlspecialchars($position->details)) !!}</div>
                        @endif
                    </td>
                    <td>{{ number_format($position->amount, 2, ',', '') }} {{ $position->unitdesignation }}</td>
                    <td class="text-right">{{ number_format($position->price, 2, ',', '') }} EUR</td>
                    <td class="text-right">{{ number_format(($position->price * $position->amount), 2, ',', '') }} EUR</td>
                </tr>
                @php $positionNumber++; @endphp
            @endif
        @endforeach
    </table>
    
    <!-- Totals -->
    <table class="totals-table">
        <tr>
            <td style="width: 18%;"></td>
            <td style="width: 67%; color: {{ $client->color ?? '#000000' }};">Gesamtbetrag netto</td>
            <td style="width: 15%; text-align: right; color: {{ $client->color ?? '#000000' }};">{{ number_format($totalSum, 2, ',', '') }} EUR</td>
        </tr>
        <tr>
            <td></td>
            <td>zzgl. Umsatzsteuer {{ $taxRate }}%</td>
            <td class="text-right">{{ number_format($totalSum * $taxRate / 100, 2, ',', '') }} EUR</td>
        </tr>
        <tr class="total-final">
            <td></td>
            <td>Gesamtbetrag brutto</td>
            <td class="text-right">{{ number_format($totalSum * ($taxRate / 100 + 1), 2, ',', '') }} EUR</td>
        </tr>
    </table>
    
    <!-- Signature Section -->
    <div class="signature-section">
        Bei Annahme des Angebots bitten wir um Unterfertigung
        
        <div class="signature-line"></div>
        <div style="margin-top: 5px;">Unterschrift Kunde</div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>
                    @if($client->companyname)
                        {{ $client->companyname }}<br>
                    @endif
                    @if($client->address)
                        {{ $client->address }}<br>
                    @endif
                    @if($client->postalcode && $client->location)
                        {{ $client->postalcode }} {{ $client->location }}<br>
                    @endif
                    Österreich
                </td>
                <td>
                    @if($client->phone)
                        Tel.: {{ $client->phone }}<br>
                    @endif
                    @if($client->email)
                        E-Mail: {{ $client->email }}<br>
                    @endif
                    @if($client->webpage)
                        Web: {{ $client->webpage }}<br>
                    @endif
                </td>
                <td>
                    @if($client->regional_court)
                        {{ $client->regional_court }}<br>
                    @endif
                    @if($client->company_registration_number)
                        FN-Nr.: {{ $client->company_registration_number }}<br>
                    @endif
                    @if($client->vat_number)
                        USt.-ID: {{ $client->vat_number }}<br>
                    @endif
                    @if($client->tax_number)
                        Steuer-Nr.: {{ $client->tax_number }}<br>
                    @endif
                    @if($client->management)
                        Geschäftsführung: {!! $client->management !!}<br>
                    @endif
                </td>
                <td>
                    @if($client->bank)
                        {{ $client->bank }}<br>
                    @endif
                    @if($client->accountnumber)
                        IBAN: {{ $client->accountnumber }}<br>
                    @endif
                    @if($client->bic)
                        BIC: {{ $client->bic }}<br>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>
</html> 