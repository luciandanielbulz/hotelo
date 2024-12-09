<form wire:submit='updateCommentDescription'>
    @if($message)
        <div x-data="{ show: @js($message ? true : false) }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition-opacity ease-linear duration-300" class="rounded-md bg-green-50 p-4">
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
    <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-2">
        <div>
            <label for="description" class="block text-sm/6 font-medium text-gray-900">Beschreibung</label>
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
