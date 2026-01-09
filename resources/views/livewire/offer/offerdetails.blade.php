
<div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
            <!-- Steuersatz -->
            <div>
                <label for="taxrateid" class="block text-sm font-bold text-gray-800 mb-2">Steuersatz</label>
                <div class="relative">
                    <select id="taxrateid" name="taxrateid" wire:model.live="taxrateid" wire:change="saveTaxrate($event.target.value)"
                            class="block w-full py-3 px-3 rounded-lg bg-white border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 hover:shadow-lg transition-all duration-200 text-gray-900 font-medium appearance-none">
                        @foreach ([1 => '0 %', 2 => '20 %'] as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Datum -->
            <div class="md:border-l md:border-gray-200/60 md:pl-6">
                <label for="offerDate" class="block text-sm font-bold text-gray-800 mb-2">Datum</label>
                <input type="date" name="offerDate" id="offerDate" wire:model.live="offerDate" wire:change="saveOfferDate($event.target.value)"
                       class="block w-full py-3 px-3 rounded-lg bg-white border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 hover:shadow-lg transition-all duration-200 text-gray-900 font-medium"/>
            </div>

            <!-- Nummer -->
            <div class="md:border-l md:border-gray-200/60 md:pl-6">
                <label for="offerNumber" class="block text-sm font-bold text-gray-800 mb-2">Nummer</label>
                <input type="text" name="offerNumber" id="offerNumber" wire:model.live="offerNumber"  
                       class="block w-full py-3 px-3 rounded-lg bg-white border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 hover:shadow-lg transition-all duration-200 text-gray-900 font-medium placeholder-gray-600"
                       placeholder="Angebotsnummer"/>
            </div>

        
        </div>

</div>