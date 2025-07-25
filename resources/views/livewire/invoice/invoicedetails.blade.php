<form wire:submit.prevent='updateInvoiceDetails'>

    @if($message)
        @if(str_contains($message, 'Fehler') || str_contains($message, 'fehlgeschlagen'))
            <div x-data="{ show: true, timeout: null }" x-init="timeout = setTimeout(() => show = false, 5000)" x-show="show" x-transition:leave="transition-opacity ease-linear duration-300" class="rounded-md bg-red-50 p-4 mb-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="size-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Fehler!</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ $message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div x-data="{ show: true, timeout: null }" x-init="timeout = setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition-opacity ease-linear duration-300" class="rounded-md bg-green-50 p-4 mb-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Erfolg!</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>{{ $message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <!-- Steuersatz -->
        <div>
            <label for="taxrateid" class="block text-sm font-bold text-gray-800 mb-1">Steuersatz</label>
            <div class="relative">
                <select id="taxrateid" name="taxrateid" wire:model="taxrateid" 
                        class="block w-full py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium appearance-none">
                    @foreach ([1 => '0 %', 2 => '20 %'] as $optionValue => $optionLabel)
                        <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                    @endforeach
                </select>
                <svg class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 w-4 h-4 text-gray-600" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Datum -->
        <div>
            <label for="invoiceDate" class="block text-sm font-bold text-gray-800 mb-1">Rechnungsdatum</label>
            <input type="date" name="invoiceDate" id="invoiceDate" wire:model="invoiceDate" 
                   class="block w-full py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium"/>
        </div>

        <!-- Nummer -->
        <div>
            <label for="invoiceNumber" class="block text-sm font-bold text-gray-800 mb-1">Nummer</label>
            <input type="text" name="invoiceNumber" id="invoiceNumber" wire:model="invoiceNumber"  
                   class="block w-full py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium placeholder-gray-600"
                   placeholder="Rechnungsnummer"/>
        </div>
    </div>

    <!-- Erweiterte Felder in separater Zeile -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
        <!-- Leistungszeitraum von -->
        <div>
            <label for="periodfrom" class="block text-sm font-bold text-gray-800 mb-1">Leistungszeitraum von</label>
            <input type="date" name="periodfrom" id="periodfrom" wire:model="periodfrom" 
                   class="block w-full py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium"/>
        </div>

        <!-- Leistungszeitraum bis -->
        <div>
            <label for="periodto" class="block text-sm font-bold text-gray-800 mb-1">Leistungszeitraum bis</label>
            <input type="date" name="periodto" id="periodto" wire:model="periodto" 
                   class="block w-full py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium"/>
        </div>

        <!-- Konditionen -->
        <div>
            <label for="condition_id" class="block text-sm font-bold text-gray-800 mb-1">Konditionen</label>
            <div class="relative">
                <select id="condition_id" name="condition_id" wire:model="condition_id" 
                        class="block w-full py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium appearance-none">
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

    <!-- Reverse Charge und Speichern Button auf gleicher Ebene -->
    <div class="flex items-center justify-between mt-4">
        @if($client && $client->smallbusiness == 0)
        <div class="flex items-center p-2 bg-gray-50 rounded-lg border border-gray-200">
            <input id="reverse_charge" name="reverse_charge" type="checkbox" wire:model.live="reverse_charge" wire:change="handleReverseChargeChange" value="1" {{ $reverse_charge ? 'checked' : '' }} class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
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

        <!-- Speichern Button rechts -->
        <button type="submit" 
                class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Speichern
        </button>
    </div>
</form>
