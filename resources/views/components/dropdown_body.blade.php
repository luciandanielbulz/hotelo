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
            class="block w-full appearance-none rounded-md bg-white px-3 py-2.5 text-base font-medium text-gray-900 border border-gray-300 placeholder:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200 sm:text-sm/6 @error($name) border-red-500 ring-red-500 @enderror">

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
        <svg
            class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 w-5 h-5 text-gray-600"
            viewBox="0 0 16 16"
            fill="currentColor"
            aria-hidden="true">
            <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
    </div>
    
    @error($name)
        <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
    @enderror
</div>
