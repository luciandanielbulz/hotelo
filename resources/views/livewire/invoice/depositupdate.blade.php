<form wire:key="unique-key-deposit" wire:submit.prevent="updateDepositAmount" method="POST">
    <label for="depositamount" class="block text-sm font-medium text-gray-900">Anzahlung in Euro</label>
    <input type="number" wire:model="depositamount" step="0.01" min="0" id="depositamount"
           class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
    <button type="submit" class="mt-4 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        Speichern
    </button>
</form>
