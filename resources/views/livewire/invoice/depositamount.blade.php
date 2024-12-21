
<div class="md:col-span-2 border-gray-900/10 pt-2 pb-4">
    <h2 class="text-base font-semibold text-gray-900">Anzahlung</h2>
    <div class="mt-2 grid md:grid-cols-2 gap-x-6 gap-y-8 sm:grid-cols-1">
        <!-- Formular für Anzahlung -->
        <div class="sm:col-span-1 md:col-span-1">
            <label for="depositamount" class="block text-sm font-medium text-gray-900">Anzahlung in Euro</label>
            <input type="number" wire:model="depositAmount" step="0.01" min="0" id="depositamount"
                class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
            <button wire:click='sendUpdateAmount' class="mt-4 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Speichern
            </button>
        </div>


    </div>
</div>
