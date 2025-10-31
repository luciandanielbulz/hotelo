
<div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
            <!-- Steuersatz -->
            <div>
                <label for="taxrateid" class="block text-sm font-bold text-gray-800 mb-2">Steuersatz</label>
                <div class="relative">
                    <select id="taxrateid" name="taxrateid" wire:model.live="taxrateid" wire:change="saveTaxrate($event.target.value)"
                            class="block w-full py-3 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium appearance-none">
                        @foreach ([1 => '0 %', 2 => '20 %'] as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 w-5 h-5 text-gray-600" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <!-- Datum -->
            <div class="md:border-l md:border-gray-200/60 md:pl-6">
                <label for="offerDate" class="block text-sm font-bold text-gray-800 mb-2">Datum</label>
                <input type="date" name="offerDate" id="offerDate" wire:model.live="offerDate" wire:change="saveOfferDate($event.target.value)"
                       class="block w-full py-3 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium"/>
            </div>

            <!-- Nummer -->
            <div class="md:border-l md:border-gray-200/60 md:pl-6">
                <label for="offerNumber" class="block text-sm font-bold text-gray-800 mb-2">Nummer</label>
                <input type="text" name="offerNumber" id="offerNumber" wire:model.live="offerNumber"  
                       class="block w-full py-3 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium placeholder-gray-600"
                       placeholder="Angebotsnummer"/>
            </div>

        
        </div>

</div>