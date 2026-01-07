<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 focus:bg-blue-800 active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300']) }}>
    {{ $slot }}
</button>
