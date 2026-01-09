@props([
    'value' => '',
    'placeholder' => '',
    'name',
    'type' => 'text',
    'label' => null,
    'step' => null, // Neues Attribut für Schrittweite
    'required' => false, // Neues Attribut für required
])

<div class="mt-1">
    <label for="{{ $name }}" class="block text-sm/6 font-bold text-gray-800 mb-2">
        {{ $label ?? ucfirst($name) }}
    </label>

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{!! $value !!}"
        @if($step)
            step="{{ $step }}"
        @endif
        @if($required)
            required
        @endif
        class="block w-full rounded-md bg-white px-3 py-2.5 text-base font-medium text-gray-900 border border-stone-200 placeholder:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 hover:shadow-lg transition-all duration-200 sm:text-sm/6 @error($name) border-red-500 ring-red-500 @enderror"
        placeholder="{{ $placeholder }}"
    />
    
    @error($name)
        <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
    @enderror
</div>
