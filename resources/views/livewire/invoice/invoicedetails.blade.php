<form wire:submit.prevent='updateInvoiceDetails'>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <!-- Spalte 1 -->
        <div>
            <!-- Steuersatz -->
            <label for="taxrateid" class="block text-sm font-bold text-gray-800 mb-1">Steuersatz</label>
            <div class="relative">
                <select id="taxrateid" name="taxrateid" wire:model.live="taxrateid" wire:change="updateInvoiceDetails"
                        class="block w-full h-11 py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium appearance-none">
                    @foreach ([1 => '0 %', 2 => '20 %'] as $optionValue => $optionLabel)
                        <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                    @endforeach
                </select>
                <svg class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 w-4 h-4 text-gray-600" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </div>
            <!-- Leistungszeitraum von -->
            <div class="mt-4">
                <label for="periodfrom" class="block text-sm font-bold text-gray-800 mb-1">Leistungszeitraum von</label>
                <input type="date" name="periodfrom" id="periodfrom" wire:model.live="periodfrom" 
                       class="block w-full h-11 py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium"/>
            </div>
        </div>

        <!-- Spalte 2 -->
        <div class="md:border-l md:border-gray-200/60 md:pl-6">
            <!-- Rechnungsdatum -->
            <label for="invoiceDate" class="block text-sm font-bold text-gray-800 mb-1">Rechnungsdatum</label>
            <input type="date" name="invoiceDate" id="invoiceDate" wire:model.live="invoiceDate" 
                   class="block w-full h-11 py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium"/>
            <!-- Leistungszeitraum bis -->
            <div class="mt-4">
                <label for="periodto" class="block text-sm font-bold text-gray-800 mb-1">Leistungszeitraum bis</label>
                <input type="date" name="periodto" id="periodto" wire:model.live="periodto" 
                       class="block w-full h-11 py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium"/>
            </div>
        </div>

        <!-- Spalte 3 -->
        <div class="md:border-l md:border-gray-200/60 md:pl-6">
            <!-- Nummer -->
            <label for="invoiceNumber" class="block text-sm font-bold text-gray-800 mb-1">Nummer</label>
            <input type="text" name="invoiceNumber" id="invoiceNumber" wire:model.debounce.600ms="invoiceNumber" wire:blur="updateInvoiceDetails" wire:keydown.enter.prevent="updateInvoiceDetails"  
                   class="block w-full h-11 py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium placeholder-gray-600"
                   placeholder="Rechnungsnummer"/>
            <!-- Konditionen -->
            <div class="mt-4">
                <label for="condition_id" class="block text-sm font-bold text-gray-800 mb-1">Konditionen</label>
                <div class="relative">
                    <select id="condition_id" name="condition_id" wire:model.live="condition_id" wire:change="updateInvoiceDetails" 
                            class="block w-full h-11 py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium appearance-none">
                        <option value="">-- Bedingung wählen --</option>
                        @foreach ($conditions as $condition)
                            <option value="{{ $condition->id }}" {{ $condition_id == $condition->id ? 'selected' : '' }}>
                                {{ $condition->conditionname }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 w-4 h-4 text-gray-600" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
                @error('condition_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Reverse Charge und Speichern Button auf gleicher Ebene -->
    <div class="flex items-center justify-between mt-4">
        @if($client && $client->smallbusiness == 0)
        <div class="flex items-center p-2 bg-gray-50 rounded-lg border border-gray-200">
            <input id="reverse_charge" name="reverse_charge" type="checkbox" wire:model.live="reverse_charge" wire:change="handleReverseChargeChange" value="1" {{ $reverse_charge ? 'checked' : '' }} class="size-4 rounded border-gray-300 text-blue-900 focus:ring-blue-700">
            <div class="ml-2">
                <label for="reverse_charge" class="text-sm font-medium text-gray-900">Reverse Charge</label>
                @if($reverse_charge)
                    <span class="ml-2 text-xs text-orange-600">⚠️ 0%</span>
                @endif
            </div>
        </div>
        @else
        <div></div> <!-- Leerer Bereich wenn kein Reverse Charge -->
        @endif

        <!-- Speichern entfällt: automatische Speicherung beim Verlassen/Ändern -->
    </div>
</form>
