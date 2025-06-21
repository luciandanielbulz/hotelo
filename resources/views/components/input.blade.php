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
    <label for="{{ $name }}" class="block text-sm/6 font-medium text-gray-900 mb-1">
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
        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error($name) border-red-500 outline-red-500 @enderror"
        placeholder="{{ $placeholder }}"
    />
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
