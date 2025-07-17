<form wire:submit.prevent='updateCommentDescription'>
    @if($message)
        @if(str_contains($message, 'Fehler') || str_contains($message, 'fehlgeschlagen'))
            <div x-data="{ show: true, timeout: null }" x-init="timeout = setTimeout(() => show = false, 5000)" x-show="show" x-transition:leave="transition-opacity ease-linear duration-300" class="rounded-lg bg-red-50 p-4 mb-6 border border-red-200">
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
            <div x-data="{ show: true, timeout: null }" x-init="timeout = setTimeout(() => show = false, 5000)" x-show="show" x-transition:leave="transition-opacity ease-linear duration-300" class="rounded-lg bg-green-50 p-4 mb-6 border border-green-200">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.236 4.53L8.23 10.661a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Erfolgreich!</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>{{ $message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <!-- Beschreibung -->
        <div>
            <label for="description" class="block text-sm font-bold text-gray-800 mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
                Beschreibung
            </label>
            <input type="text" name="description" id="description" wire:model="description"  
                   class="block w-full py-3 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium placeholder-gray-600"
                   placeholder="Kurze Beschreibung des Angebots"/>
        </div>

        <!-- Kommentar -->
        <div>
            <label for="comment" class="block text-sm font-bold text-gray-800 mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Kommentar
            </label>
            <input type="text" name="comment" id="comment" wire:model="comment"  
                   class="block w-full py-3 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium placeholder-gray-600"
                   placeholder="Interner Kommentar"/>
        </div>

        <!-- Speichern Button -->
        <div>
            <button type="submit" 
                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl w-32">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Speichern
            </button>
        </div>
    </div>
</form>
