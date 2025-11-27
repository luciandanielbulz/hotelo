@props([
    'name',
    'label',
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'required' => false,
])

<div class="mt-1">
    <label for="{{ $name }}" class="block text-sm/6 font-bold text-gray-800 mb-2">
        {{ $label }}
    </label>

    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required)
                required
            @endif
            style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none;"
            class="block w-full rounded-md bg-white px-3 py-2.5 pr-10 text-base font-medium text-gray-900 border border-gray-300 placeholder:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 sm:text-sm/6 @error($name) border-red-500 ring-red-500 @enderror">

            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $selected == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        
        <!-- Pfeil-Icon -->
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg
                class="h-5 w-5 text-gray-600"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    
    @error($name)
        <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
    @enderror
</div>
