<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-blue-900 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
