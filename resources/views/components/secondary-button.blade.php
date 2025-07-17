<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-white border-2 border-gray-400 rounded-lg font-semibold text-sm text-gray-800 shadow-md hover:bg-gray-100 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:shadow-lg disabled:opacity-25 transition-all duration-300']) }}>
    {{ $slot }}
</button>
