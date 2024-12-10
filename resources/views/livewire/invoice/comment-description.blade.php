<form wire:submit='updateCommentDescription'>
    <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-2">
        <div>
            <label for="description" class="block text-sm/6 font-medium text-gray-900">Beschreibung - erscheint nicht in Rechnung!</label>
            <div class="mt-1">
                <input type="text" name="description" id="description" wire:model="description"  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
            </div>

        </div>
        <div>
            <label for="comment" class="block text-sm/6 font-medium text-gray-900">Kommentar</label>
            <div class="mt-1">
                <input type="text" name="comment" id="comment" wire:model="comment"  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
            </div>
        </div>
        <div >
            <button class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Speichern</button>
        </div>
    </div>
