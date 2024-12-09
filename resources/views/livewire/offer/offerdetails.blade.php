
    <form wire:submit='updateDetails'>
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
        <div class="mt-2 grid grid-cols-3 gap-x-6 gap-y-2">
            <!-- Steuersatz -->
            <div>
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
                    class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 w-4 h-4 text-gray-500"
                    viewBox="0 0 16 16"
                    fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </div>
            
            

            <!-- Datum -->
            <div >
                <label for="offerDate" class="block text-sm/6 font-medium text-gray-900">Datum</label>
                <div class="mt-1">
                    <input type="date" name="offerDate" id="offerDate" wire:model="offerDate" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
                </div>
            </div>

            <!-- Nummer -->
            
            <div>
                <label for="offerNumber" class="block text-sm/6 font-medium text-gray-900">Nummer</label>
                <div class="mt-1">
                    <input type="number" name="offerNumber" id="offerNumber" wire:model="offerNumber"  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"/>
                </div>
            </div>

            <div >
                <button class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Speichern</button>
            </div>
            
        </div>   
    </form>
    
