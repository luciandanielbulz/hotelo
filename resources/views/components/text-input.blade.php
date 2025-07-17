@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full rounded-md bg-white px-3 py-2.5 text-base font-medium text-gray-900 border border-gray-300 placeholder:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200']) !!}>
