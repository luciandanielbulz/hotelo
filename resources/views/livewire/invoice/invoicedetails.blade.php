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
    <div class="grid md:grid-cols-4 gap-x-6 gap-y-8 sm:grid-cols-1">
        <!--Steuersatz-->
        <div class="relative">
            <label for="taxrateid" class="block text-sm/6 font-medium text-gray-900">Steuersatz</label>
            <div class="mt-1">
                <select id="taxrateid" name="taxrateid" wire:model="taxrateid" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    @foreach ([1 => '0 %', 2 => '20 %'] as $optionValue => $optionLabel)
                        <option value="{{ $optionValue }}">
                            {{ $optionLabel }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Pfeil-Icon -->
            <svg
                class="pointer-events-none absolute top-9 right-3  w-4 h-4 text-gray-500"
                viewBox="0 0 16 16"
                fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </div>

        <!-- Datum -->
        <div > 
            <label for="invoiceDate" class="block text-sm/6 font-medium text-gray-900">Rechnungsdatum</label>
            <div class="mt-1">
                <input type="date" name="invoiceDate" id="invoiceDate" wire:model="invoiceDate" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
            </div>
        </div>

        <!-- Leistungszeitraum von -->
        <div >
            <label for="periodfrom" class="block text-sm/6 font-medium text-gray-900">Leistungszeitraum von</label>
            <div class="mt-1">
                <input type="date" name="periodfrom" id="periodfrom" wire:model="periodfrom" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
            </div>
        </div>

        <!-- Leistungszeitraum bis -->
        <div >
            <label for="periodto" class="block text-sm/6 font-medium text-gray-900">Leistungszeitraum bis</label>
            <div class="mt-1">
                <input type="date" name="periodto" id="periodto" wire:model="periodto" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
            </div>
        </div>

        <div>
            <label for="invoiceNumber" class="block text-sm/6 font-medium text-gray-900">Nummer</label>
            <div class="mt-1">
                <input type="string" name="invoiceNumber" id="invoiceNumber" wire:model="invoiceNumber"  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
            </div>
        </div>


        <div class ="relative">
            <label for="condition_id" class="block text-sm/6 font-medium text-gray-900">Konditionen</label>
            <div class="mt-1">
                <select id="condition_id" name="condition_id" wire:model="condition_id" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    <option value="">-- Bedingung wählen --</option>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ $condition_id == $condition->id ? 'selected' : '' }}>
                            {{ $condition->conditionname }}
                        </option>
                    @endforeach
                </select>

                <!-- Pfeil-Icon -->
                <svg
                    class="pointer-events-none absolute top-9 right-3  w-4 h-4 text-gray-500"
                    viewBox="0 0 16 16"
                    fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </div>
            @error('condition_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Reverse Charge - nur anzeigen wenn NICHT Kleinunternehmer -->
        @if($client && $client->smallbusiness == 0)
        <div class="flex items-center">
            <div class="flex h-6 items-center">
                <input id="reverse_charge" name="reverse_charge" type="checkbox" wire:model.live="reverse_charge" wire:change="handleReverseChargeChange" value="1" {{ $reverse_charge ? 'checked' : '' }} class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
            </div>
            <div class="ml-3 text-sm/6">
                <label for="reverse_charge" class="font-medium text-gray-900">Reverse Charge</label>
                <p class="text-gray-500">Steuerschuldnerschaft des Leistungsempfängers</p>
                @if($reverse_charge)
                    <p class="text-orange-600 text-xs mt-1 font-medium">⚠️ Steuersatz wird automatisch auf 0% gesetzt</p>
                @endif
            </div>
        </div>
        @endif
    </div>
    <div class="grid md:grid-cols-4 gap-x-6 gap-y-8 sm:grid-cols-1 mt-4">
        <div >
            <button type="submit" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Speichern</button>
        </div>

    </div>
</form>
