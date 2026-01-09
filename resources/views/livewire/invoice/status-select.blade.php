<div>
    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Status
    </h2>


    <div>
        <label for="status" class="block text-sm font-bold text-gray-800 mb-1">Status ändern</label>
        <div class="relative">
			<select id="status" name="status" wire:model.live="status"
                    class="block w-full h-11 py-2.5 px-3 rounded-lg bg-white border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 hover:shadow-lg transition-all duration-200 text-gray-900 font-medium appearance-none">
                @foreach($statusOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            
        </div>
    </div>
</div>


