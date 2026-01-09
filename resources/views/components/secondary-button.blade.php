<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-white border-2 border-gray-400 rounded-lg font-semibold text-sm text-gray-800 hover:bg-gray-100 hover:border-gray-500 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 disabled:opacity-25 disabled:hover:scale-100']) }}>
    {{ $slot }}
</button>
