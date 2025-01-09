<div>
    <form wire:submit.prevent="performSearch" class="flex items-center space-x-2">
        <input type="text" placeholder="Kunden suchen..." wire:model.defer="searchTerm"
            class="w-64 flex-grow p-2 border border-gray-300 rounded" />
        <button type="submit" class="w-64 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Suchen</button>
    </form>

    @if($customers && $customers->count())
        <!-- Tabelle für Kundendaten -->
        <table class="min-w-full divide-y mt-2 divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Firma</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontakt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktion</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($customers as $customer)
                    <tr class="hover:bg-gray-100 cursor-pointer" wire:click="selectCustomer({{ $customer->id }})">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $customer->companyname }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $customer->customername }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-blue-600 hover:underline">Auswählen</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-gray-500">Keine Kunden gefunden.</div>
    @endif
</div>
