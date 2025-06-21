<div class="md:col-span-2  border-gray-900/10 pt-2 pb-4">
        <!-- Formular für Anzahlung -->

        <div class="mt-2 grid md:grid-cols-2">
        <!-- Zusammenfassung der Beträge -->
        <div class="sm:col-span-1 md:col-span-1 text-right">
            <p>Zwischensumme (Netto):</p>
            <p>zzgl. Umsatzsteuer ({{ $invoice->reverse_charge ? '0' : $invoice->taxrate }} %):</p>
            <p class="font-semibold border-t border-b">Gesamtsumme:</p>

            @if ($depositAmount > 0)
                <p class="border-t border-b">Anzahlung:</p>
                <p class="font-semibold border-t border-b-2">Zu Zahlen:</p>
            @endif
        </div>


        <!-- Werte für Zusammenfassung -->
        <div class="m-3 text-right md:col-span-1">
            <p>{{ number_format($total_price, 2, ',', '.') }} €</p>
            <p>{{ number_format($invoice->reverse_charge ? 0 : $total_price * ($tax_rate / 100), 2, ',', '.') }} €</p>
            <p class="font-semibold border-t border-b">{{ number_format($invoice->reverse_charge ? $total_price : $total_price * (1 + ($tax_rate / 100)), 2, ',', '.') }} €</p>

            @if ($depositAmount > 0)
                <p class="border-t border-b">{{ number_format($invoice->depositamount * -1, 2, ',', '.') }} €</p>
                <p class="font-semibold border-t border-b-2">{{ number_format(($invoice->reverse_charge ? $total_price : $total_price * (1 + ($tax_rate / 100))) - $invoice->depositamount, 2, ',', '.') }} €</p>
            @endif
        </div>

        <!-- Reverse Charge Hinweis nach der Gesamtsumme -->
        @if($invoice->reverse_charge)
        <div class="md:col-span-2 mt-3">
            <p class="text-sm text-orange-600 italic text-center">
                Umsatzsteuer wird gemäß § 13b UStG vom Leistungsempfänger geschuldet
            </p>
        </div>
        @endif

</div>
