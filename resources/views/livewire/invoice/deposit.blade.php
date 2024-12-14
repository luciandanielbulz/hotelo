<div class="border-b border-t border-gray-900/10 pt-2 pb-4">
    <h2 class="text-base font-semibold text-gray-900">Anzahlung</h2>
    <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
        <!-- Formular für Anzahlung -->
        <div class="sm:col-span-2 md:col-span-2">
            <form wire:submit.prevent="updateDepositAmount">
                <label for="depositamount" class="block text-sm font-medium text-gray-900">Anzahlung in Euro</label>
                <input type="number" wire:model="depositamount" step="0.01" min="0" id="depositamount"
                       class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                <button class="mt-4 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Speichern
                </button>
            </form>
        </div>

        <!-- Leere Spalte für Abstand -->
        <div class="sm:col-span-2"></div>

        <!-- Zusammenfassung der Beträge -->
        <div class="sm:col-span-2 text-right">
            <p>Zwischensumme (Netto):</p>
            <p>Umsatzsteuer ({{ $details->taxrate }} %):</p>
            <p class="font-semibold border-t border-b">Gesamtsumme:</p>

            @if ($details->depositamount > 0)
                <p class="border-t border-b">Anzahlung:</p>
                <p class="font-semibold border-t border-b-2">Zu Zahlen:</p>
            @endif
        </div>

        <!-- Werte für Zusammenfassung -->
        <div class="sm:col-span-2 text-left">
            <p>{{ number_format($total_price->total_price, 2, ',', '.') }} €</p>
            <p>{{ number_format($total_price->total_price * ($details->taxrate / 100), 2, ',', '.') }} €</p>
            <p class="font-semibold border-t border-b">{{ number_format($total_price->total_price * (1 + ($details->taxrate / 100)), 2, ',', '.') }} €</p>

            @if ($details->depositamount > 0)
                <p class="border-t border-b">{{ number_format($details->depositamount * -1, 2, ',', '.') }} €</p>
                <p class="font-semibold border-t border-b-2">{{ number_format($total_price->total_price * (1 + ($details->taxrate / 100)) - $details->depositamount, 2, ',', '.') }} €</p>
            @endif
        </div>
    </div>
</div>
