
<div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4 border border-orange-200">
    <!-- Header mit Icon -->
    <div class="flex items-center mb-3">
        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
        </svg>
        <h2 class="text-sm font-medium text-orange-700">Anzahlung</h2>
    </div>

    <!-- Eingabebereich -->
    <div class="space-y-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-orange-600 font-medium text-sm">€</span>
            </div>
            <input type="number" 
                   wire:model="depositAmount" 
                   step="0.01" 
                   min="0" 
                   id="depositamount"
                   placeholder="0,00"
                   class="block w-full pl-8 pr-3 py-2 rounded-lg bg-white border border-orange-300 text-gray-900 font-medium placeholder:text-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
        </div>

        <!-- Speichern Button -->
        <button wire:click='sendUpdateAmount' 
                class="w-full inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 text-sm">
            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Speichern
        </button>
    </div>
</div>
