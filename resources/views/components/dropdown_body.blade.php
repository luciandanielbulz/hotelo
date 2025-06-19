<div class="mt-1">
    <label for="{{ $name }}" class="block text-sm/6 font-medium text-gray-900 mb-1">
        {{ $label }}
    </label>

    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            class="block w-full appearance-none rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">

            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $selected == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        
        <!-- Pfeil-Icon -->
        <svg
            class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 w-4 h-4 text-gray-500"
            viewBox="0 0 16 16"
            fill="currentColor"
            aria-hidden="true">
            <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
    </div>
</div>
