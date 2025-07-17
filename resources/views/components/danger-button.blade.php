<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:from-red-700 hover:to-pink-700 focus:from-red-700 focus:to-pink-700 active:from-red-800 active:to-pink-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transition-all duration-300']) }}>
    {{ $slot }}
</button>
