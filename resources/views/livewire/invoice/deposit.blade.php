<div class="border-b border-t border-gray-900/10 pt-2 pb-4">
    <h2 class="text-base font-semibold text-gray-900">Anzahlung</h2>
    <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
        <!-- Formular für Anzahlung -->
        <div class="sm:col-span-2 md:col-span-2">
            <form wire:submit.prevent="updateDepositAmount">
                <label for="depositamount" class="block text-sm font-bold text-gray-800 mb-2">Anzahlung in Euro</label>
                <input type="number" wire:model="depositamount" step="0.01" min="0" id="depositamount"
                       class="mt-2 block w-full rounded-md bg-white px-3 py-2.5 text-base font-medium text-gray-900 border border-gray-300 placeholder:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 sm:text-sm">
                <button class="mt-4 inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:shadow-xl transition-all duration-300">
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
