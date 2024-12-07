@props(['value' => '', 'placeholder' => '', 'name', 'type' => 'text', 'label' => null])


<div class="sm:col-span-3">
    <label for="{{ $name }}" class="block text-sm/6 font-medium text-gray-900">
        {{ $label ?? ucfirst($name) }}
    </label>
    <div class="mt-1">
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value }}"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            placeholder="{{ $placeholder }}"
        />
    </div>
</div>

