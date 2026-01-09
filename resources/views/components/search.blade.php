@props([
    'model' => 'search',
    'placeholder' => 'Suchen...',
])

<div class="relative">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>
    <input wire:model.live="{{ $model }}" 
           type="text" 
           placeholder="{{ $placeholder }}" 
           {{ $attributes->merge(['class' => 'block w-full pl-10 pr-3 py-3  rounded-lg bg-white/50 border border-stone-200 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-700 shadow-sm text-gray-900 placeholder-gray-500']) }}>
</div>
